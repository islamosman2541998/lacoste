<?php

namespace Database\Seeders;

use App\Models\StoreSetting;
use Illuminate\Database\Seeder;

class StoreSettingSeeder extends Seeder
{
    public function run(): void
    {
        StoreSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'store_name_ar' => 'متجري',
                'store_name_en' => 'My Store',
                'currency_code' => 'EGP',
                'currency_symbol' => 'EGP',
                'default_locale' => 'ar',
                'is_store_active' => true,
            ]
        );
    }
}