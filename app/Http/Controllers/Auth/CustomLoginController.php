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

    private function formatPhoneNumber($phone)
        {
            // Убираем все лишние символы из строки
            $digits = preg_replace('/\D/', '', $phone);

            // Преобразуем номер, если он соответствует формату 8707... или 7707...
            if (preg_match('/^8?(707|700|701|702|705|707|777|747|727|776|778|747|747|750|751)(\d{3})(\d{2})(\d{2})$/', $digits, $matches)) {
                return "+7 ({$matches[1]}) {$matches[2]}-{$matches[3]}-{$matches[4]}";
            }

            // Возвращаем номер без изменений, если формат не подходит
            return $phone;
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
        if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
            // Предполагаем, что это номер телефона, и форматируем его
            $login = $this->formatPhoneNumber($login);
        }

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
