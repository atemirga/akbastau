<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Создание ролей
        $adminRole = Role::create(['name' => 'admin']);
        $coordinatorRole = Role::create(['name' => 'coordinator']);
        $userRole = Role::create(['name' => 'user']);

        // Создание пользователя-администратора
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.kz',
            'password' => Hash::make('pa$$w0rd'),
        ]);

        // Назначение роли админа пользователю
        $admin->assignRole($adminRole);
    }
}

