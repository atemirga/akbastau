<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserImportController extends Controller
{
    public function importForm()
    {
        return view('import-users');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        $data = Excel::toArray([], $file)[0];

        foreach ($data as $key => $row) {
            // Пропускаем первую строку (заголовки)
            if ($key === 0) {
                continue;
            }

            $name = $row[0]; // ФИО
            $position = $row[1]; // Должность
            $phone = $row[2]; // Телефон
            $departmentName = $row[3]; // Отдел

            // Проверка, существует ли отдел
            $department = Department::firstOrCreate(['name' => $departmentName]);

            // Проверка, существует ли пользователь
            $existingUser = User::where('phone', $phone)->first();
            if ($existingUser) {
                // Если пользователь существует, обновляем отдел
                $existingUser->department_id = $department->id;
                $existingUser->save();
                continue;
            }

            // Создаем пользователя
            $user = User::create([
                'name' => $name,
                'phone' => $phone,
                'password' => Hash::make('password123'), // Временный пароль
                'department_id' => $department->id,
            ]);

            // Назначаем роль (если роль ещё не создана, добавьте её логически)
            $user->role = 'user'; // Указываем роль пользователя
            $user->save();
        }

        return back()->with('success', 'Данные успешно импортированы.');
    }
}
