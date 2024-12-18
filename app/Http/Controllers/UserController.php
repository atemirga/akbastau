<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all(); // Получаем всех пользователей
        $departments = Department::all(); // Получаем все отделы
        return view('pages.users', compact('users', 'departments'));
    }

    public function store(Request $request)
    {
        // Валидация данных
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string'
        ]);



        try {
            // Создание пользователя
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'current_team_id' => $request->department_id,
                'role' => $request->role,
                'password' => Hash::make('pa$$w0rd'),
            ]);

            return redirect()->route('users.index')->with('success', 'Пользователь успешно создан с паролем pa$$w0rd.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Если возникает ошибка дублирования
            if ($e->getCode() === '23000') { // Код ошибки дублирования
                return redirect()->route('users.index')->with('error', 'Ошибка: Такой номер телефона уже зарегистрирован.');
            }

            // Для других ошибок
            return redirect()->route('users.index')->with('error', 'Произошла ошибка. Попробуйте еще раз.');
        }
    }


    // Метод для обновления данных пользователя
    public function update(Request $request, User $user)
    {
        // Валидация данных
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'department_id' => 'nullable|exists:departments,id',
            'role' => 'required|in:admin,coordinator,employee',
        ]);

        // Обновление данных пользователя
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'current_team_id' => $request->department_id,
            'role' => $request->role,
        ]);

        // Перенаправление с сообщением об успешном обновлении
        return redirect()->route('users.index')->with('success', 'Данные пользователя успешно обновлены.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => 'Пользователь удалён']);
    }

}
