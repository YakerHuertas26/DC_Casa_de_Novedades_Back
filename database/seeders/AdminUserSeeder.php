<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // rol admin 
        $adminRole= Role::create([
            'name'=>'admin',
            'guard_name'=>'api'

        ]);

        // user admin
        $adminUser=User::create([
            'name'=>'DC Casa de Novedades',
            'userName'=>'casa de novedades',
            'email'=>'dc_casa_de_novedades@gmail.com',
            'password'=>Hash::make(env('ADMIN_PASSWORD')),
            'keyWord'=>'casa',
            'state'=>true,
        ]);

        // asigno el rol 
        $adminUser->assignRole($adminRole);
    }
}
