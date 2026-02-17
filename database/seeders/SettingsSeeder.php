<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Read existing settings.json
        if (Storage::exists('settings.json')) {
            $json = Storage::get('settings.json');
            $data = json_decode($json, true);

            if ($data) {
                foreach ($data as $key => $value) {
                    Setting::setValue($key, $value);
                }
                $this->command->info('Settings migrated from settings.json to database.');
            }
        } else {
            $this->command->warn('No settings.json found. Skipping migration.');
        }
    }
}
