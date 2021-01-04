<?php

use Illuminate\Database\Seeder;
use App\Terminal;

class TerminalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Terminal::create([
            'name'=> '#1 Dongle'
        ]);
        Terminal::create([
            'name'=> '#2 WG 79221614'
        ]);
        Terminal::create([
            'name'=> '#3 WG 79221619'
        ]);
    }
}
