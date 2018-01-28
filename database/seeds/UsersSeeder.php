<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = [
            [
                'name' => 'juan',
                'email' => 'cruz.jm.stafe@gmail.com',
                'password' => bcrypt('pertennesco')
            ], [
                'name' => 'mobile',
                'email' => 'mobile.app@mail.com',
                'password' => bcrypt('ggj2018YAY!')
            ]
        ];

        DB::table('users')->insert($values);
    }
}
