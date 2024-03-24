      @if (count($errors) > 0)
      <div class="ui error icon message">
        <i class="ion-alert-circled icon"></i>
        <i class="close icon"></i>
        <div class="content">
          <div class="header">
            {{ trans('status.header.error') }}
          </div>
          <ul class="list">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
      @endif

      @if (session('status') || session('info'))
      <div class="ui info icon message">
        <i class="ion-android-alert icon"></i>
        <i class="close icon"></i>
        <div class="content">
          <p>{{ session('status') ?: session('info') }}</p>
        </div>
      </div>
      @endif

      @if (session('success'))
      <div class="ui success icon message">
        <i class="ion-checkmark-circled icon"></i>
        <i class="close icon"></i>
        <div class="content">
          <p>{{ session('success') }}</p>
        </div>
      </div>
      @endif

      @if (session('warning'))
      <div class="ui warning icon message">
        <i class="ion-alert icon"></i>
        <i class="close icon"></i>
        <div class="content">
          <p>{{ session('warning') }}</p>
        </div>
      </div>
      @endif

      @if (session('warnings'))
      <div class="ui warning icon message">
        <i class="ion-alert icon"></i>
        <i class="close icon"></i>
        <div class="content">
          <div class="header">
            {{ trans('status.header.warning') }}
          </div>
          <ul class="list">
            @foreach ($session(warnings) as $warning)
              <li>{{ $warning }}</li>
            @endforeach
          </ul>
        </div>
      </div>
      @endif
