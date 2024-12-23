<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CustomLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Валидация, использующая поле 'login' вместо 'email'
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        // Определяем, является ли login email или телефоном
        $user = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? User::where('email', $login)->first()
            : User::where('phone', $login)->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user);
            if (auth()->user()->role !== 'admin') {
                return redirect()->intended('/tickets'); // перенаправление на страницу после входа
                //abort(403, 'У вас нет доступа к этой странице.');
            }else{
                //abort(403, 'У вас нет доступа к этой странице.');
                return redirect()->intended('/dashboard'); // перенаправление на страницу после входа
            }

            //return redirect()->intended('/dashboard'); // перенаправление на страницу после входа
        }

        return back()->withErrors([
            'login' => 'Неверные учетные данные.',
        ]);
    }
}
