<?php

namespace App\Repositories;

use App\Models\Shopping;
use App\Repositories\Interfaces\ShoppingRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ShoppingRepository implements ShoppingRepositoryInterface
{
    public function getShopping(): ?Shopping
    {
        return Shopping::first();
    }

    public function update(array $data): Shopping
    {
        $shopping = $this->getShopping();

        if (! $shopping) {
            $shopping = $this->createIfNotExists();
        }

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($shopping->image && Storage::disk('public')->exists($shopping->image)) {
                Storage::disk('public')->delete($shopping->image);
            }

            $data['image'] = $data['image']->store('shoppings', 'public');
        }

        $shopping->update($data);

        return $shopping->fresh();
    }

    public function createIfNotExists(): Shopping
    {
        return Shopping::firstOrCreate(
            [],
            [
                'text_header' => '',
                'text_description' => '',
                'footer_short_description' => '',
            ]
        );
    }
}
