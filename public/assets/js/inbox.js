(function($insura, $) {
    $(document).ready(function() {
        function get12HourTime(dateTime) {
            var time = (new Date(dateTime + ' UTC')).toString().split(' ')[4].split(':');
            time[0] = parseInt(time[0]);
            return time[0] > 12 ? (time[0] - 12) + ':' + time[1] + ' PM' : (time[0] === 0 ? '12': time[0]) + ':' + time[1] + ' AM';
        }
        function get24HourTime(dateTime) {
            var time = (new Date(dateTime + ' UTC')).toString().split(' ')[4].split(':');
            return time[0] + ':' + time[1];
        }
        function scrollInboxToBottom() {
            $('div.inbox-right').scrollTop($('div.inbox-right')[0].scrollHeight);
        }
        // Load Audio Messages
        var messageSentAudio = new Audio($insura.urls.audio.sent);
        var newMessageAudio = new Audio($insura.urls.audio.new);

        // Configure chat events
        function newMessagesHandler(e) {
            var chatMessages = JSON.parse(e.data || e.insuraMessages);
            $.each(chatMessages, function(index, chatMessage) {
                var chatElement = $('div.chat[data-chat="' + chatMessage.sender_id + '"]');
                var personElement = $('li.person[data-person="' + chatMessage.sender_id + '"]');
                // Add new message bubble
                chatElement.append('<div class="bubble you" data-message="' + chatMessage.id + '">' + chatMessage.message + '<span>' + get24HourTime(chatMessage.created_at) + '</span></div>');
                // Add new message to preview
                personElement.find('span.preview').attr('data-message', chatMessage.id).text(chatMessage.message).attr('data-message', chatMessage.id);
                personElement.find('span.status').attr('data-message', chatMessage.id).find('i').attr('class', 'icon');
                personElement.find('span.time').text(get12HourTime(chatMessage.created_at));
                if(chatElement.hasClass('active-chat')) {
                    // See chat message
                    $('form.write input[name="recipient"]').change();
                    // Scroll inbox to bottom
                    scrollInboxToBottom();
                }else {
                    // Add to the message counter
                    var nameElement = personElement.find('span.name');
                    var counterElement = nameElement.find('span.label');
                    if(counterElement > 0) {
                        counterElement.text(parseInt(counterElement.text().trim()) + 1);
                    }else {
                        nameElement.append('<span class="ui tiny green circular label">1</span>');
                    }
                }  
                // Show person
                personElement.show();
            });
            newMessageAudio.play();
        }
        function receivedMessagesHandler(e) {
            var chatMessages = JSON.parse(e.data || e.insuraMessages);
            $.each(chatMessages, function(index, chatMessage) {
                $('div.bubble[data-message="' + chatMessage.id + '"] i').toggleClass('ion-android-done ion-android-done-all');
                $('li.person span.status[data-message="' + chatMessage.id + '"] i').attr('class', 'ion-android-done-all');
            });
        }
        function seenMessagesHandler(e) {
            var chatMessages = JSON.parse(e.data || e.insuraMessages);
            $.each(chatMessages, function(index, chatMessage) {
                $('div.bubble[data-message="' + chatMessage.id + '"] i').attr('class', 'ion-android-done-all blue icon');
                $('li.person span.status[data-message="' + chatMessage.id + '"] i').attr('class', 'ion-android-done-all message-seen icon');
            });
        }
        if(typeof(EventSource) !== "undefined") {
            var es = new EventSource($insura.urls.chats.live);
            es.addEventListener('InsuraNewMessages', newMessagesHandler);
            es.addEventListener('InsuraReceivedMessages', receivedMessagesHandler);
            es.addEventListener('InsuraSeenMessages', seenMessagesHandler);
            $(window).on('unload', function() {
                es.close();
            });
        }else {
            var dummyElement = $('<button/>');
            dummyElement.on('InsuraNewMessages', newMessagesHandler);
            dummyElement.on('InsuraReceivedMessages', receivedMessagesHandler);
            dummyElement.on('InsuraSeenMessages', seenMessagesHandler);
            (function longPoll() {
                $.get($insura.urls.chats.live, null, null, 'json').done(function(result) {
                    var event = $.Event(result.event, result.data);
                    dummyElement.trigger(event);
                }).always(longPoll);
            })();
        }

        // Add new chat
        $('input#contact').change(function() {
            var inputElement = $(this);
            $('div.inbox-left li.person[data-person="' + inputElement.val() + '"]').trigger('mousedown').remove().appendTo('ul.people').show();
            // Remove the contact's "item" element from selection
            $('div.inbox-left div.selection div.item[data-value="' + inputElement.val() + '"]').remove()
            // Reset the selection
            inputElement.val('X');
            inputElement.parent().find('div.text').html(inputElement.parent().find('div.disabled').addClass('active selected').html());
            // Scroll list to bottom
            $('div.inbox-left').scrollTop($('div.inbox-left')[0].scrollHeight);
        });
        
        // Toggle active person and chat
        $('div.inbox-left').on('mousedown', 'li.person[data-person]', function() {
            if($(this).hasClass('.active')) {
                // Chat already active
                return false;
            }else if($(this).attr('data-person') === 'X') {
                // New chat request
                $('div.selection i.dropdown').click();
                return false; 
            }else {
                var personId = $(this).attr('data-person');
                var personName = $(this).find('span.name')[0].outerHTML.match(/">[a-zA-Z0-9\s]*</)[0].replace('">', '').replace('<', '');
                // Update the 'To:' section in the inbox view
                $('div.inbox-right div.top span.name').html(personName);
                // Toggle active classes
                $('div.chat.active-chat').removeClass('active-chat');
                $('div.inbox-left li.person.active').removeClass('active');
                $(this).addClass('active');
                $('div.chat[data-chat="' + personId + '"]').addClass('active-chat');
                // Update message recipient
                $('form.write input[name="recipient"]').val(personId).change();
                // Focus input on message field
                $('form.write input[name="message"]').focus().attr('disabled', false);
                // Scroll inbox to bottom
                scrollInboxToBottom();
            }
            if($(window).width() < 640){
                $('.inbox-right').show();
                $('.inbox-left').hide();
            }
        });
        $(".inbox-back").click(function(){
            $('.inbox-right').hide();
            $('.inbox-left').show();
        });
        $(window).resize(function() {
            if($(window).width() > 639){
                $('.inbox-right').show();
                $('.inbox-left').show();
            }
        });

        // See chats
        $('form.write input[name="recipient"]').change(function(e) {
            $.post($insura.urls.chats.see, {
                sender: e.target.value
            }).done(function() {
                // Remove counter label
                $('li.person[data-person="' + e.target.value + '"] span.label').remove();
            });
        });

        // Handle form submit
        $('form.write').submit(function(e, callback) {
            var newBubbleElement, recipientId = $('input[name="recipient"]').val();
            var newBubbleId = recipientId + '-' + Date.now(), personElement = $('li.person[data-person="' + recipientId + '"]');
            e.preventDefault();
            $.ajax({
                beforeSend: function() {
                    var messageInput = $('form.write input[name="message"]');
                    personElement.find('span.preview').attr('data-message', newBubbleId).text(messageInput.val());
                    personElement.find('span.status').attr('data-message', newBubbleId).html('<i class="ion-android-time grey icon"></i>');
                    newBubbleElement = $('<div class="bubble me" data-message="' + newBubbleId + '">' + messageInput.val() + '<i class="ion-android-time grey icon"></i><span></span></div>').appendTo('div.chat.active-chat');
                    messageInput.val('');
                },
                contentType: false,
                data: new FormData(e.target),
                method: 'POST',
                processData: false,
                url: $insura.urls.chats.send
            }).done(function(chatMessage) {
                // Add new data to the message bubble
                newBubbleElement.attr('data-message', chatMessage.id);
                newBubbleElement.find('i.icon').toggleClass('ion-android-time ion-android-done');
                newBubbleElement.find('span').text(get24HourTime(chatMessage.created_at));// Add new message to preview
                // Add new message to preview
                personElement.find('span.preview[data-message="' + newBubbleId + '"]').attr('data-message', chatMessage.id).prev().text(get12HourTime(chatMessage.created_at));
                personElement.find('span.status[data-message="' + newBubbleId + '"]').attr('data-message', chatMessage.id).find('i.icon').toggleClass('ion-android-time ion-android-done');
                // Scroll inbox to bottom
                scrollInboxToBottom();
                // Play audio
                messageSentAudio.play();
            }).fail(function() {
                newBubbleElement.find('i.icon').toggleClass('ion-android-time grey red ion-alert-circled red').attr('data-tooltip', 'Click to retry');
            });
            if(!!callback && typeof callback === 'function') {
                callback();
            }
        });

        // Retry a failed message
        $('div.chat').on('click', 'i.ion-alert-circled', function() {
            var bubbleElement = $(this).parents('div.bubble:first'),
                messageInput = $('form.write input[name="message"]'),
                recipientInput = $('form.write input[name="recipient"]');
            var currentValues = {
                message: messageInput.val(),
                recipient: recipientInput.val()
            };
            var failedValues = {
                message: bubbleElement.text().trim(),
                recipient: bubbleElement.attr('data-message').split('-')[0] // 0 - recipient, 1 - timestamp
            };
            messageInput.val(failedValues.message);
            recipientInput.val(failedValues.message);
            $('form.write').trigger('submit', (function(cV, mI, rI) {
                return function() {
                    mI.val(cV.message);
                    rI.val(cV.recipient);
                };
            })(currentValues, messageInput, recipientInput));
        });

        /**
         * Initialization
         */

        // Remove counter labels on header and sidebar
        $('header a.insura-chats span.label, aside a.insura-chats span.label').remove();

        // Change message times from UTC to the browser timezone's time
        $('div.inbox-right div.bubble > span').each(function(i, element) {
            element.innerText = get24HourTime(element.innerText);
        });
        $('div.inbox-left span.time').each(function(i, element) {
            if(!!element.innerText.match(/[0-9]*-[0-9]*-[0-9]*\s[0-9]*:[0-9]*:[0-9]*/)) {
                element.innerText = get12HourTime(element.innerText);
            }
        });
        
        // Activate default chat
        if($insura.vars.lastChatee !== 'X') {
            $('li.person[data-person="' + $insura.vars.lastChatee + '"]').trigger('mousedown');
        }
    });
})(window.insura, window.jQuery);