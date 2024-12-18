@extends('layouts.app', [
    'class' => 'login-page',
    'backgroundImagePath' => 'img/bg/fabio-mangione.jpg'
])

@section('content')
    <div class="content">
        <div class="container">
            <div class="col-lg-4 col-md-6 ml-auto mr-auto">
                <form class="form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="card card-login">
                        <div class="card-header">
                            <h3 class="header text-center">{{ __('Вход') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="nc-icon nc-single-02"></i></span>
                                </div>
                                <!-- Поле для ввода email или телефона -->
                                <input class="form-control{{ $errors->has('login') ? ' is-invalid' : '' }}"
                                       placeholder="{{ __('Email или Телефон') }}"
                                       type="text"
                                       name="login"
                                       value="{{ old('login') }}"
                                       required autofocus>
                                @if ($errors->has('login'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                        <strong>{{ $errors->first('login') }}</strong>
                    </span>
                                @endif
                            </div>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="nc-icon nc-key-25"></i></span>
                                </div>
                                <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password"
                                       placeholder="{{ __('Пароль') }}"
                                       type="password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" name="remember" type="checkbox" value="" {{ old('remember') ? 'checked' : '' }}>
                                        <span class="form-check-sign"></span>
                                        {{ __('Запомнить меня') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-center">
                                <button type="submit" class="btn btn-warning btn-round mb-3">{{ __('Войти') }}</button>
                            </div>
                        </div>
                    </div>
                </form>

                <!--
                <form class="form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="nc-icon nc-single-02"></i>
                            </span>
                        </div>
                        <input class="form-control{{ $errors->has('login') ? ' is-invalid' : '' }}" placeholder="Email или Телефон" type="text" name="login" value="{{ old('login') }}" required autofocus>
                        @if ($errors->has('login'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('login') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="nc-icon nc-key-25"></i>
                            </span>
                        </div>
                        <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Пароль" type="password" required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" style="display: block;" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-warning btn-round mb-3">Войти</button>
                    </div>
                </form>
                -->
                <a href="{{ route('password.request') }}" class="btn btn-link">
                    {{ __('Забыли пароль?') }}
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            demo.checkFullPageBackgroundImage();
        });
    </script>
@endpush
