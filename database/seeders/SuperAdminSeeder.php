<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Business;
use Illuminate\Support\Str;
use App\Models\BusinessUser;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    protected static ?string $password;

    public function run(): void
    {
        $businessIds = Business::all()->pluck('id')->toArray();

        $data = User::create([
            'name' => 'SuperAdmin',
            'email' => 'superadmin@mail.com',
            'email_verified_at' => now(),
            'is_super_admin' => true,
            'is_active' => true,
            'password' => static::$password ??= Hash::make('123123123'),
            'remember_token' => Str::random(10),
        ]);

        $data->businesses()->attach($businessIds);

        BusinessUser::find(1)->update([
            'is_active' => true,
        ]);

        $data->assignRole('SuperAdmin');

    }
}
