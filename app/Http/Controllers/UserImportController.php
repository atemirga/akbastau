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
            $phone = $this->formatPhoneNumber($row[2]); // Исправлено
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
                'email' => $phone.'@mail.ru',
                'phone' => $phone,
                'password' => Hash::make('pa$$w0rd'), // Временный пароль
                'department_id' => $department->id,
                'role' => 'employee',
            ]);

            $user->save();
        }

        return back()->with('success', 'Данные успешно импортированы.');
    }

    private function formatPhoneNumber($phone)
    {
        // Удаляем все символы, кроме цифр
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        // Если номер не содержит нужное количество цифр, возвращаем его как есть
        if (strlen($cleaned) !== 11) {
            return $phone;
        }

        // Разбиваем номер на части и форматируем
        return sprintf('+7 (%s) %s-%s-%s',
            substr($cleaned, 1, 3), // Код оператора
            substr($cleaned, 4, 3), // Первая часть номера
            substr($cleaned, 7, 2), // Вторая часть номера
            substr($cleaned, 9, 2)  // Третья часть номера
        );
    }
}
