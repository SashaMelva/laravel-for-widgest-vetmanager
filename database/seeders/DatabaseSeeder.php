<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        DB::table('api_settings')->insert([
            'user_id' => '1',
            'url' => 'sashamel',
            'key' => '58160e1141a1abcfb54ecc42266c7d84',
        ]);

        DB::table('users')->insert([

            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('secret')
        ]);
    }
}
