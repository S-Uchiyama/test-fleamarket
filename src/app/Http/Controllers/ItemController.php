<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab     = $request->query('tab');
        $keyword = $request->query('keyword');

        if ($tab === 'mylist') {
            if (!auth()->check()) {
                $items = collect();
            } else {
                $items = auth()->user()
                    ->likedItems()
                    ->with(['categories', 'purchase'])
                    ->when($keyword, function ($q) use ($keyword) {
                        $q->where('name', 'like', '%' . $keyword . '%');
                    })
                    ->latest()
                    ->get();
            }
        } else {
            $items = Item::with(['categories', 'purchase'])
                ->when(auth()->check(), function ($q) {
                    $q->where('user_id', '!=', auth()->id());
                })
                ->when($keyword, function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                })
                ->latest()
                ->get();
        }

        return view('items.index', compact('items'));
    }

    public function show(Item $item)
    {
        $item->load([
            'categories',
            'comments.user',
            'likes',
            'purchase',
        ]);

        $isLiked = auth()->check()
            ? $item->likes->contains('user_id', auth()->id())
            : false;

        return view('items.show', compact('item', 'isLiked'));
    }

    public function create()
    {
        $categories = Category::orderBy('id')->get();

        return view('items.sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $user = $request->user();

        $path = $request->file('image')->store('items', 'public');

        $imgPath = Storage::url($path);

        $item = Item::create([
            'user_id'    => $user->id,
            'name'       => $request->name,
            'price'      => $request->price,
            'brand'      => $request->brand ?? null,
            'description'=> $request->description,
            'condition'  => $request->condition,
            'img_path'   => $imgPath,
        ]);

        $item->categories()->sync($request->category_ids);

        return redirect()->route('items.show', $item)
            ->with('status', '商品を出品しました');
    }
}
