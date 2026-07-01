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
        $admin = User::firstOrCreate([
            'email' => 'admin@venajon.com',
        ], [
            'name' => 'Admin',
            'password' => bcrypt('Venajon@65'),
        ]);

        $admin->assignRole('Admin');
    }
}
