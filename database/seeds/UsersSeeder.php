<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    protected $values = [
        ['name' => 'Hideo Kojima', 'score' => 0, 'player' => '']
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('influencers')->insert($this->values);
    }
}
