<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $tab = $request->query('page', 'sell');

        if ($tab === 'buy') {
            $items = Item::whereHas('purchase', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->with('purchase')
                ->latest()
                ->get();
        } else {
            $items = $user->items()
                ->with('purchase')
                ->latest()
                ->get();

            $tab = 'sell';
        }

        return view('mypage.index', [
            'user'  => $user,
            'items' => $items,
            'tab'   => $tab,
        ]);
    }
}
