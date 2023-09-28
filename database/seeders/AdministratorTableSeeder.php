<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Administrator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class AdministratorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrators1 = [
                'role_id' => '1',
                'first_name' => 'Pooja',
                'last_name' => 'Mogra',
                'email' => 'pooja@gmail.com',
                'password' => bcrypt('pooja@'),
                'phone_number' => '9898989898',
                'last_login' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
        ]; 
        $AdministratorInfo1 = Administrator::create($administrators1);
        $AdministratorInfo1->assignRole('Super Admin');
        $AdministratorInfo1->syncPermissions(Permission::all());
        
        $administrators2 = [
                'role_id' => '2',
                'first_name' => 'Palak',
                'last_name' => 'Mogra',
                'email' => 'palak@gmail.com',
                'password' => bcrypt('palak@'),
                'phone_number' => '9898989898',
                'last_login' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
        ];
        $AdministratorInfo2 = Administrator::create($administrators2);
        $AdministratorInfo2->assignRole('Users');
        // $AdministratorInfo2->givePermissionTo([
        //     'add-administrator',
        // ]);
    }
}
