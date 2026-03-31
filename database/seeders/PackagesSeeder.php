<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackagesSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name'          => 'Başlangıç',
                'slug'          => 'baslangic',
                'description'   => 'Sistemi keşfetmek için ideal başlangıç paketi.',
                'credit_amount' => 10,
                'price'         => 4900,
                'currency'      => 'TRY',
                'badge_label'   => null,
                'badge_color'   => null,
                'features'      => json_encode(['10 deneme kabini', 'HD görsel çıktı', '30 gün görsel saklama']),
                'is_active'     => true,
                'is_featured'   => false,
                'sort_order'    => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Pro',
                'slug'          => 'pro',
                'description'   => 'Düzenli kullananlar için en popüler seçim.',
                'credit_amount' => 30,
                'price'         => 9900,
                'currency'      => 'TRY',
                'badge_label'   => 'En Popüler',
                'badge_color'   => 'primary',
                'features'      => json_encode(['30 deneme kabini', 'HD görsel çıktı', '30 gün görsel saklama', 'Öncelikli işleme']),
                'is_active'     => true,
                'is_featured'   => true,
                'sort_order'    => 2,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'name'          => 'Kurumsal',
                'slug'          => 'kurumsal',
                'description'   => 'Yoğun kullanım ve işletmeler için.',
                'credit_amount' => 100,
                'price'         => 24900,
                'currency'      => 'TRY',
                'badge_label'   => 'En İyi Değer',
                'badge_color'   => 'success',
                'features'      => json_encode(['100 deneme kabini', 'HD görsel çıktı', '30 gün görsel saklama', 'Öncelikli işleme', 'Öncelikli destek']),
                'is_active'     => true,
                'is_featured'   => false,
                'sort_order'    => 3,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ];

        foreach ($packages as $package) {
            DB::table('packages')->updateOrInsert(
                ['slug' => $package['slug']],
                $package
            );
        }
    }
}