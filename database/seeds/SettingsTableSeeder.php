<?php

use Illuminate\Database\Seeder;
use App\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'key' => 'saldo',
            'value' => 0
        ]);
        date_default_timezone_set('Asia/Jakarta');
        Setting::create([
            'key' => 'lastupdate',
            'value' => date('Y-m-d H:i:s', time())
        ]);
    }
}
