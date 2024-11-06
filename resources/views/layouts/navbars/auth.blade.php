<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
        <a href="http://www.creative-tim.com" class="simple-text logo-mini">
            <div class="logo-image-small">
                <img src="{{ asset('paper') }}/img/logo-small.png">
            </div>
        </a>
        <a href="{{ route('dashboard') }}" class="simple-text logo-normal">
            {{ __('Оптимус') }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="{{ $elementActive == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="nc-icon nc-bank"></i>
                    <p>{{ __('Панель управления') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'tickets' ? 'active' : '' }}">
                <a href="{{ route('tickets.index') }}">
                    <i class="nc-icon nc-paper"></i> <!-- Обновите иконку, если нужно -->
                    <p>{{ __('Предложение') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'map' ? 'active' : '' }}">
                <a href="{{ route('map') }}">
                    <i class="nc-icon nc-pin-3"></i>
                    <p>{{ __('Месторождения') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'notifications' ? 'active' : '' }}">
                <a href="{{ route('notifications') }}">
                    <i class="nc-icon nc-bell-55"></i>
                    <p>{{ __('Уведомления') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'users' ? 'active' : '' }}">
                <a href="{{ route('users') }}">
                    <i class="nc-icon nc-single-02"></i> <!-- Обновляем иконку на иконку пользователя -->
                    <p>{{ __('Пользователи') }}</p>
                </a>
            </li>

            <li class="{{ $elementActive == 'logbook' ? 'active' : '' }}">
                <a href="{{ route('logs') }}">
                    <i class="nc-icon nc-book-bookmark"></i> <!-- Обновляем иконку на соответствующую журналу или записи -->
                    <p>{{ __('Журнал записи') }}</p>
                </a>
            </li>
        </ul>
    </div>
</div>
