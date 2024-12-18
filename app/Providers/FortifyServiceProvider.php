<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Proposal;
use Illuminate\Support\Facades\DB;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Переопределение представления для логина
        Fortify::loginView(function () {
            return view('auth.login');
        });


        Fortify::authenticateUsing(function (Request $request) {
            // Проверка, является ли поле email или phone
            $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

            $user = User::where($loginField, $request->login)->first();

            if ($user && Auth::attempt([$loginField => $request->login, 'password' => $request->password])) {
                return $user;
            }
            return null;
        });
        /**
        // Кастомная логика аутентификации
        Fortify::authenticateUsing(function (Request $request) {
            // Настройка кастомной валидации, чтобы использовать 'login' вместо 'email'
            $request->validate([
                'login' => 'required|string',
                'password' => 'required|string',
            ]);

            $login = $request->input('login');
            $password = $request->input('password');

            // Определение, является ли login email или телефоном
            $user = filter_var($login, FILTER_VALIDATE_EMAIL)
                ? User::where('email', $login)->first()
                : User::where('phone', $login)->first();

            if ($user && Hash::check($password, $user->password)) {
                return $user;
            }

            return null;
        }); **/
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
