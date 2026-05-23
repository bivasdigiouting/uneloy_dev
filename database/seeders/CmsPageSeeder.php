<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CmsPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            'Terms & Condition',
            'Privacy Policy',
            'Refund Policy',
            'Shipping Policy',
        ];

        foreach ($pages as $page) {
            \App\Models\CmsPage::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($page)],
                [
                    'title' => $page,
                    'content' => '<h1>' . $page . '</h1><p>Content coming soon...</p>',
                    'status' => true,
                ]
            );
        }
    }
}
