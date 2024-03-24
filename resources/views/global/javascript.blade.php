(function($) {
    window.insura = {
        helpers: {
            initAccordion: function(selector) {
                $(selector).accordion();
            },
            initDatepicker: function(selector) {
                (typeof selector === 'string' ? $(selector) : selector).datepicker({
                    autoHide: true,
                    format: 'yyyy-mm-dd'
                });
            },
            initDropdown: function(selector) {
                (typeof selector === 'string' ? $(selector) : selector).dropdown({
                    transition: 'drop'
                });
            },
            initDropify: function(selector) {
                $(selector).dropify();
            },
            initModal: function(selector, clear) {
                $(selector).modal({
                    onApprove : function(button) {
                        var modal = button.closest('div.modal');
                        var form = modal.find('form');
                        if(form[0].checkValidity()) {
                            form.submit();
                            return true;
                        }else {
                            form.append('<button style="display:none;" type="submit"></button>').find(':submit').click().remove();
                            return false;
                        }
                    },
                    onDeny: function(button) {
                        if(!!clear) {
                            button.closest('div.modal').find('form').append('<button style="display:none;" type="reset"></button>').find(':reset').click().remove()
                        }
                    }
                });
            },
            initScrollbar: function(selector) {
                $(selector).scrollbar();
            },
            initSwal: function(selector, options) {
                $(selector).click(function(e) {
                    e.preventDefault();
                    swal({
                        title: options.title,
                        text: options.text,
                        type: "warning",
                        cancelButtonText: '{{ trans('communication.swal.warning.delete.cancel') }}',
                        confirmButtonColor: "#ff1a1a",
                        confirmButtonText: options.confirmText,
                        showCancelButton: true,
                        closeOnConfirm: false
                    }, function() {
                        $(e.target).parents('form:first').trigger('submit');
                    });
                });
            },
            initTabs: function(selector) {
                $(selector).tab();
            },
            initTelInput: function(selector) {
                (typeof selector === 'string' ? $(selector) : selector).blur(function(e) {
                    var element = $(e.target);
                    if ($.trim(element.val())) {
                        if (!element.intlTelInput('isValidNumber')) {
                            e.target.setCustomValidity('{{ trans('communication.message.error.invalid.phone') }}');
                            element.parents('div.field:first').addClass('error');
                        }else{
                            e.target.setCustomValidity('');
                            element.parents('div.field:first').removeClass('error');
                        }
                    }
                }).intlTelInput({
                    autoPlaceholder: 'aggressive',
                    formatOnDisplay: true,
                    hiddenInput: 'phone',
                    placeholderNumberType: "FIXED_LINE_OR_MOBILE",
                    utilsScript: '{{ asset('assets/libs/intl-tel-input/js/utils.js') }}'
                });
            },
            listenForChats: function() {
                // Load Audio Messages
                var newMessageAudio = new Audio('{{ asset('uploads/audio/new-message.mp3') }}');

                // Configure chat events
                function newMessagesHandler(e) {
                    var chatMessages = JSON.parse(e.data || e.insuraMessages);
                    $.each(chatMessages, function(index, chatMessage) {
                        var headerElement = $('header a.insura-chats');
                        var sidebarElement = $('aside a.insura-chats');
                        // Add to the message counter
                        var counterElements = headerElement.find('span.label').add(sidebarElement.find('span.label'));
                        if(counterElements.length > 0) {
                            var count = parseInt(counterElements[0].innerText.trim()) + 1;
                            counterElements.text(count);
                        }else {
                            headerElement.append('<span class="ui red circular floating label">1</span>');
                            sidebarElement.prepend('<span class="ui red circular label pull-right">1</span>');
                        }
                    });
                    newMessageAudio.play();
                }
                if(typeof(EventSource) !== "undefined") {
                    var es = new EventSource('{{ action('ChatController@live', array(
                        'quiet' => true
                    )) }}');
                    es.addEventListener('InsuraNewMessages', newMessagesHandler);
                    $(window).on('unload', function() {
                        es.close();
                    });
                }else {
                    var dummyElement = $('<button/>');
                    dummyElement.on('InsuraNewMessages', newMessagesHandler);
                    (function longPoll() {
                        $.get('{{ action('ChatController@live', array(
                            'quiet' => true
                        )) }}', null, null, 'json').done(function(result) {
                            var event = $.Event(result.event, result.data);
                            dummyElement.trigger(event);
                        }).always(longPoll);
                    })();
                }
            },
            requireDropdownFields: function(selector) {
                (typeof selector === 'string' ? $(selector) : selector).each(function(index, element) {
                    $(element).parent().find('input.search').blur(function(event) {
                        if(element.value !== '') {
                            event.target.setCustomValidity('');
                        }else {
                            event.target.setCustomValidity('{{ trans('settings.message.error.required') }}')
                        }
                    }).blur();
                });
            }
        },
        urls: {
            audio: {
                new: '{{ asset('uploads/audio/new-message.mp3') }}',
                sent: '{{ asset('uploads/audio/message-sent.mp3') }}'
            },
            chats: {
                live: '{{ action('ChatController@live') }}',
                see: '{{ action('ChatController@see') }}',
                send: '{{ action('ChatController@send') }}'
            }
        }
    };
})(window.jQuery);
