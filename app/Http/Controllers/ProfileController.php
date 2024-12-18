<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit'); //
    }

    public function update(Request $request)
    {
        // Здесь добавьте логику для обновления профиля
        // Например, валидация и сохранение данных

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            // Добавьте другие поля, которые нужно обновить
        ]);

        $user = auth()->user();
        $user->update($request->only(['name', 'email', 'phone']));

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if (Hash::check($request->old_password, auth()->user()->password)) {
            auth()->user()->update(['password' => Hash::make($request->password)]);
            return redirect()->route('profile.edit')->with('status', 'Пароль успешно изменен.');
        }

        return back()->withErrors(['old_password' => 'Старый пароль неверен.']);
    }

}
