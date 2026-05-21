<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class HomeSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'hero_is_visible',
                'value' => '1',
                'type' => 'boolean',
            ],
            [
                'key' => 'hero_title',
                'value' => 'Zadbaj o swój ogród z Nevro-Shop',
                'type' => 'string',
            ],
            [
                'key' => 'hero_description',
                'value' => 'Odkryj naszą ofertę zbiorników IBC i akcesoriów ogrodowych, które sprawią, że Twoja praca w ogrodzie stanie się przyjemnością.',
                'type' => 'string',
            ],
            [
                'key' => 'hero_button_text',
                'value' => 'Odkryj ofertę',
                'type' => 'string',
            ],
            [
                'key' => 'hero_button_link',
                'value' => '/catalog',
                'type' => 'string',
            ],
            [
                'key' => 'hero_image_url',
                'value' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDgWv9KS-hPHuh5egM4qGzxabvc2h-ZWLigFvYfrWcNrK8XDsnIbSOWz_eO4lt-b_Z5s3lve5lvXFvTbC6qvOhDEnG3yrIPRFW6c5z7vT7Uw56zntVR55YfQNcQIIJOSjSD9OaWf_ugwHkMdVNQX4-wMVbL0s5MYa0V66dTxN2NuqnbwciyGL7CUSm900B6uhFjPb6wMo1vJxTfGvJDwU5kp-8c9Y05RnrycXz65ECe_rupN0xUvGe9S8lDrpOxyt7oyU181v03iH06',
                'type' => 'image',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
