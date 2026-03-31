<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['group' => 'general', 'key' => 'site_name',         'value' => 'TryOn',                          'type' => 'string',  'label' => 'Site Adı',              'is_public' => true],
            ['group' => 'general', 'key' => 'site_tagline',      'value' => 'Sanal Deneme Kabini',            'type' => 'string',  'label' => 'Slogan',                'is_public' => true],
            ['group' => 'general', 'key' => 'contact_email',     'value' => 'info@tryon.com',                 'type' => 'string',  'label' => 'İletişim E-postası',    'is_public' => true],
            ['group' => 'general', 'key' => 'welcome_credits',   'value' => '2',                              'type' => 'integer', 'label' => 'Hoş Geldin Kredisi',    'is_public' => false],
            ['group' => 'general', 'key' => 'referral_credits',  'value' => '10',                             'type' => 'integer', 'label' => 'Referans Bonusu',       'is_public' => false],
            ['group' => 'general', 'key' => 'image_expiry_days', 'value' => '30',                             'type' => 'integer', 'label' => 'Görsel Saklama (Gün)',  'is_public' => false],
            ['group' => 'general', 'key' => 'credits_per_tryon', 'value' => '1',                              'type' => 'integer', 'label' => 'Deneme Başı Kredi',     'is_public' => true],
            ['group' => 'seo',     'key' => 'meta_title',        'value' => 'TryOn - Sanal Deneme Kabini',   'type' => 'string',  'label' => 'Meta Başlık',            'is_public' => true],
            ['group' => 'seo',     'key' => 'meta_description',  'value' => 'Kıyafetleri satın almadan önce sanal olarak deneyin.', 'type' => 'string', 'label' => 'Meta Açıklama', 'is_public' => true],
            ['group' => 'seo',     'key' => 'meta_keywords',     'value' => 'sanal deneme, kıyafet, AI, moda', 'type' => 'string', 'label' => 'Anahtar Kelimeler',    'is_public' => true],
            ['group' => 'payment', 'key' => 'polarsh_api_key',   'value' => null, 'type' => 'string', 'label' => 'Polarsh API Key',     'is_public' => false],
            ['group' => 'payment', 'key' => 'polarsh_secret_key','value' => null, 'type' => 'string', 'label' => 'Polarsh Secret Key',  'is_public' => false],
            ['group' => 'payment', 'key' => 'polarsh_mode',      'value' => 'sandbox', 'type' => 'string', 'label' => 'Ödeme Modu',      'is_public' => false],
            ['group' => 'api',     'key' => 'wiro_api_key',      'value' => null, 'type' => 'string', 'label' => 'Wiro API Key',         'is_public' => false],
            ['group' => 'api',     'key' => 'wiro_api_url',      'value' => 'https://api.wiro.ai', 'type' => 'string', 'label' => 'Wiro API URL', 'is_public' => false],
            ['group' => 'social',  'key' => 'instagram_url',     'value' => null, 'type' => 'string', 'label' => 'Instagram', 'is_public' => true],
            ['group' => 'social',  'key' => 'twitter_url',       'value' => null, 'type' => 'string', 'label' => 'Twitter/X', 'is_public' => true],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}