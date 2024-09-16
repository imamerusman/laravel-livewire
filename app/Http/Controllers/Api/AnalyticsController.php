<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductAnalyticsRequest;
use App\Models\ProductAnalytics;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function store(ProductAnalyticsRequest $request)
    {
        $product = ProductAnalytics::updateOrCreate(
            [
                'shopify_product_id' => $request->input('shopify_product_id'),
            ],
            [
                'name' => $request->input('name'),
            ]
        );
        if ($request->has('views')) {
            $product->update([
                'views' => $request->input('views') + $product->views
            ]);
        }
        if ($request->has('search')) {
            $product->update([
                'searches' => $request->input('search') + $product->searches
            ]);
        }
        if ($request->has('sales')) {
            $product->update([
                'sales' => $request->input('sales') + $product->sales
            ]);
        }
        return $this->success('Product analytics stored successfully.');
    }

    public function getSearchProductAnalytics()
    {
        $searchedProducts = ProductAnalytics::query()
            ->select('name')
            ->selectRaw('SUM(searches) as searches')
            ->groupBy('name')
            ->orderByDesc('searches')
            ->limit(10)
            ->get()
            ->groupBy('name')
            ->map(function ($productAnalyticsCollection) {
                return $productAnalyticsCollection->sum('searches');
            });
        $totalSearch = $searchedProducts;

        return [
            'label' => 'Most Search Product',
            'labels' => $totalSearch->keys(),
            'total_search' => $totalSearch->values()->toArray()
        ];
    }

    public function getSalesProductAnalytics()
    {
        $saleProducts = ProductAnalytics::query()
            ->select('name')
            ->selectRaw('SUM(sales) as sales')
            ->groupBy('name')
            ->orderByDesc('sales')
            ->limit(10)
            ->get();
        $totalSale = $saleProducts->mapToGroups(function ($item) {
            return [$item->name => $item->sales];
        })->map(function ($sales) {
            return $sales->sum();
        });

        return [
            'label' => 'Most Sales Product',
            'labels' => $totalSale->keys(),
            'total_search' => $totalSale->values()->toArray()
        ];
    }

    public function getViewProductAnalytics()
    {
        $viewProducts = ProductAnalytics::query()
            ->select('name')
            ->selectRaw('SUM(views) as views')
            ->groupBy('name')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        $totalViews = $viewProducts->groupBy('name')
            ->map(function ($productAnalyticsCollection) {
                return $productAnalyticsCollection->sum('views');
            });

        return [
            'label' => 'Most View Products',
            'labels' => $totalViews->keys(),
            'total_search' => $totalViews->values()->toArray()
        ];
    }
}
