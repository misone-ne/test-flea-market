<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::latest()->get();

        return view('products.index', compact('items'));
    }

    public function show($item_id)
    {
        return "商品ID: " . $item_id . "の詳細ページ（予定）";
    }
}
