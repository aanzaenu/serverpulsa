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
            'name'=> '#1 Dongle',
            'terminal_id' => 1,
            'saldo' => 0
        ]);
        Terminal::create([
            'name'=> '#2 WG 79221614',
            'terminal_id' => 38,
            'saldo' => 0
        ]);
        Terminal::create([
            'name'=> '#3 WG 79221619',
            'terminal_id' => 39,
            'saldo' => 0
        ]);
    }
}
