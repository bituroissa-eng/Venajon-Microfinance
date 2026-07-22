<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate([
            'email' => 'admin@venajon.co.tz',
        ], [
            'name' => 'Admin',
            'password' => bcrypt('12345678'),
        ]);

        $admin->assignRole('Admin');
    }
}
