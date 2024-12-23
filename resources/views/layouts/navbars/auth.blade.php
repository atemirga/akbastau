<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
        <a href="http://www.creative-tim.com" class="simple-text logo-mini">
            <div class="logo-image-small">
                <img src="{{ asset('paper') }}/img/logo-small.png">
            </div>
        </a>
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('dashboard') }}" class="simple-text logo-normal">
            {{ __('Оптимус') }}
        </a>
        @endif
        @if(auth()->user()->role !== 'admin')
        <a href="{{ route('tickets.index') }}" class="simple-text logo-normal">
            {{ __('Оптимус') }}
        </a>
        @endif

    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            @if(auth()->user()->role === 'admin')
            <li class="{{ $elementActive == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="nc-icon nc-bank"></i>
                    <p>{{ __('Панель управления') }}</p>
                </a>
            </li>
            @endif
            <li class="{{ $elementActive == 'tickets' ? 'active' : '' }}">
                <a href="{{ route('tickets.index') }}">
                    <i class="nc-icon nc-paper"></i> <!-- Обновите иконку, если нужно -->
                    <p>{{ __('Предложение') }}</p>
                </a>
            </li>
            <!--
            <li class="{{ $elementActive == 'map' ? 'active' : '' }}">
                <a href="{{ route('map') }}">
                    <i class="nc-icon nc-pin-3"></i>
                    <p>{{ __('Месторождения') }}</p>
                </a>
            </li>
            -->
            
            @if (auth()->check() && auth()->user()->role == 'admin')
                <li class="{{ $elementActive == 'departments' ? 'active' : '' }}">
                    <a href="{{ route('departments.index') }}">
                        <i class="nc-icon nc-bank"></i> <!-- Обновляем иконку на иконку здания -->
                        <p>{{ __('Отделы') }}</p>
                    </a>
                </li>
                <li class="{{ $elementActive == 'users' ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}">
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
            @endif
            <li class="{{ $elementActive == 'notifications' ? 'active' : '' }}">
                <a href="{{ route('notifications.index') }}">
                    <i class="nc-icon nc-bell-55"></i>
                    <p>
                        {{ __('Уведомления') }}
                        @if($unreadCount > 0) <!-- Показываем бейдж, только если есть непрочитанные уведомления -->
                            <span class="badge badge-danger">{{ $unreadCount }}</span>
                        @endif
                    </p>
                </a>
            </li>

        </ul>
    </div>
</div>
