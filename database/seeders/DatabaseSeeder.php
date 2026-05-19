<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
        ]);

        User::create([
            'name' => 'Иванова Ольга Ивановна',
            'email' => 'leader1@test.ru',
            'phone' => '+79990000001',
            'password' => Hash::make('12345678'),
            'role' => 'leader',
            'photo' => 'driver1.png',
        ]);

        User::create([
            'name' => 'Петрова Анна Сергеевна',
            'email' => 'leader2@test.ru',
            'phone' => '+79990000002',
            'password' => Hash::make('12345678'),
            'role' => 'leader',
            'photo' => 'driver2.png',
        ]);

        User::create([
            'name' => 'Смирнова Мария Андреевна',
            'email' => 'leader3@test.ru',
            'phone' => '+79990000003',
            'password' => Hash::make('12345678'),
            'role' => 'leader',
            'photo' => 'driver3.png',
        ]);

        User::create([
            'name' => 'Иванов Иван Иванович',
            'email' => 'user@test.ru',
            'phone' => '+79990000004',
            'password' => Hash::make('12345678'),
            'role' => 'visitor',
            'photo' => null,
        ]);
    }
}
