<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Response;
use Helmesvs\Notify\Facades\Notify;

class ItemsController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return response($items->jsonSerialize());
    }

    public function create(Request $request)
    {
        $item = Item::create([
           'item_name' => $request->item_name,
           'is_selected' => 0
        ]);

       return response(['items' => $item->jsonSerialize()]);
    }

    public function move(Request $request)
    {
        Item::where(['item_name' => $request->item_name])
            ->update(['is_selected' => $request->isSelected]);

        return response(['status' => true]);
    }
}
