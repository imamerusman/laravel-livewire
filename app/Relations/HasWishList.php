<?php

namespace App\Relations;

use App\Models\WishList;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

trait HasWishList
{
    public function getMyWishListAttribute(): Collection
    {
        /** @var WishList $wishList */
        $wishList = $this->wishList()->firstOrCreate([
            'customer_id' => $this->id,
        ], [
            'items' => collect()
        ]);
        return $wishList->items;
    }

    public function wishList(): HasOne
    {
        return $this->hasOne(WishList::class);
    }

    public function getWishListModelAttribute(): WishList
    {
        /** @var WishList $wishList */
        $wishList = $this->wishList()->firstOrCreate([
            'customer_id' => $this->id,
        ], [
            'items' => collect()
        ]);
        return $wishList;
    }

    public function addToWishList(Collection|string $items): Collection
    {
        if (is_string($items)) {
            $items = [$items];
        }

        $wishlistItems = $this->my_wish_list->concat($items)->unique();

        $this->wishList->update(['items' => $wishlistItems]);
        return $wishlistItems;
    }


    public function syncWishList(string $item): Collection
    {
        $list = $this->my_wish_list;

        if ($list->contains($item)) {
            $list = $list->filter(fn($i) => $i !== $item);
        } else {
            $list->push($item);
        }
        $this->wishList->update(['items' => $list]);

        return $list;
    }
}
