<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->foreignId('gallery_album_id')->nullable()->after('id')->constrained('gallery_albums')->nullOnDelete();
        });

        $existingCount = DB::table('gallery_images')->count();
        if ($existingCount === 0) {
            return;
        }

        $distinctTitles = DB::table('gallery_images')
            ->select('title')
            ->distinct()
            ->pluck('title')
            ->map(function ($t) {
                $t = is_string($t) ? trim($t) : '';

                return $t !== '' ? $t : 'Gallery';
            })
            ->unique()
            ->values();

        DB::transaction(function () use ($distinctTitles) {
            foreach ($distinctTitles as $title) {
                $slugBase = Str::slug($title);
                $slug = $slugBase !== '' ? $slugBase : 'gallery';
                $suffix = 1;

                while (DB::table('gallery_albums')->where('slug', $slug)->exists()) {
                    $suffix++;
                    $slug = $slugBase.'-'.$suffix;
                }

                $firstImage = DB::table('gallery_images')
                    ->where(function ($q) use ($title) {
                        if ($title === 'Gallery') {
                            $q->whereNull('title')->orWhere('title', '');
                        } else {
                            $q->where('title', $title);
                        }
                    })
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->value('image');

                $albumId = DB::table('gallery_albums')->insertGetId([
                    'title' => $title,
                    'slug' => $slug,
                    'cover_image' => $firstImage,
                    'sort_order' => 0,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('gallery_images')
                    ->where(function ($q) use ($title) {
                        if ($title === 'Gallery') {
                            $q->whereNull('title')->orWhere('title', '');
                        } else {
                            $q->where('title', $title);
                        }
                    })
                    ->update([
                        'gallery_album_id' => $albumId,
                    ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropConstrainedForeignId('gallery_album_id');
        });
    }
};
