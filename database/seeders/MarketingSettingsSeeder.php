<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class MarketingSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'google_ads_id' => [
                'value' => 'AW-17898708559',
                'type' => 'string',
            ],
            'google_analytics_id' => [
                'value' => 'G-C31ZKYZ9R4',
                'type' => 'string',
            ],
            'google_tag_manager_id' => [
                'value' => '',
                'type' => 'string',
            ],
        ];

        foreach ($settings as $key => $data) {
            Setting::updateOrCreate(
                ['key' => $key],
                $data
            );
        }
    }
}
