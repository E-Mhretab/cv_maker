<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CvMetadataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metadata = [
            [
                'id' => 1,
                'cv_id' => 1,
                'template_type' => 1,
                'is_public' => 1,
                'published_at' => '2025-09-20 10:00:00',
                'created_at' => '2025-09-20 10:00:00',
                'updated_at' => '2025-09-20 10:00:00'
            ],
            [
                'id' => 2,
                'cv_id' => 2,
                'template_type' => 2,
                'is_public' => 0,
                'published_at' => null,
                'created_at' => '2025-09-21 14:30:00',
                'updated_at' => '2025-09-21 14:30:00'
            ]
        ];

        foreach ($metadata as $meta) {
            \DB::table('cv_metadata')->updateOrInsert(
                ['id' => $meta['id']],
                $meta
            );
        }
    }
}
