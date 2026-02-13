<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session as StripeCheckoutSession;
use Stripe\Stripe;

class PurchaseController extends Controller
{
    public function show(Item $item)
    {
        $user = Auth::user()->fresh();

        // 自分の商品は購入できない
        if ($item->user_id === $user->id) {
            return redirect()
                ->route('items.show', $item)
                ->with('error', '自分の商品は購入できません。');
        }

        // すでに購入済みなら購入できない
        if ($item->purchase()->exists() || $item->sold_at) {
            return redirect()
                ->route('items.show', $item)
                ->with('error', 'この商品はすでに購入されています。');
        }

        return view('purchase.show', [
            'item'    => $item,
            'user'    => $user,
        ]);
    }

    public function store(PurchaseRequest $request, Item $item)
    {
        $user = $request->user();

        if (app()->environment('testing')) {
            return $this->completePurchase($request, $item, $user);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentMethod = $request->payment_method;
        $paymentTypes = $paymentMethod === 'convenience_store' ? ['konbini'] : ['card'];

        $request->session()->put('purchase_payload', [
            'payment_method' => $paymentMethod,
            'postcode' => $request->input('postcode', $user->postcode),
            'address' => $request->input('address', $user->address),
            'building' => $request->input('building', $user->building),
        ]);

        $session = StripeCheckoutSession::create([
            'mode' => 'payment',
            'payment_method_types' => $paymentTypes,
            'customer_email' => $user->email,
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
            ]],
            'success_url' => route('purchase.success', $item) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('purchase.cancel', $item),
        ]);

        return redirect()->away($session->url);
    }

    public function success(Request $request, Item $item)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()
                ->route('items.show', $item)
                ->with('error', '決済情報が確認できませんでした。');
        }

        $session = StripeCheckoutSession::retrieve($sessionId);
        if (!in_array($session->payment_status, ['paid', 'unpaid'], true)) {
            return redirect()
                ->route('items.show', $item)
                ->with('error', '決済が完了していません。');
        }

        $payload = $request->session()->pull('purchase_payload', []);
        if (empty($payload)) {
            return redirect()
                ->route('items.show', $item)
                ->with('error', '購入情報が確認できませんでした。');
        }

        $request->merge($payload);

        if ($session->payment_status === 'unpaid') {
            return $this->completePurchase($request, $item, $request->user(), 'pending');
        }

        return $this->completePurchase($request, $item, $request->user(), 'paid');
    }

    public function cancel(Item $item)
    {
        return redirect()
            ->route('purchase.show', $item)
            ->with('error', '決済がキャンセルされました。');
    }

    private function completePurchase(Request $request, Item $item, $user, string $status = 'paid')
    {
        return DB::transaction(function () use ($request, $item, $user, $status) {
            $lockedItem = Item::whereKey($item->id)->lockForUpdate()->first();

            if ($lockedItem->user_id === $user->id) {
                return redirect()
                    ->route('items.show', $lockedItem)
                    ->with('error', '自分の商品は購入できません。');
            }

            if ($lockedItem->purchase()->exists() || $lockedItem->sold_at) {
                return redirect()
                    ->route('items.show', $lockedItem)
                    ->with('error', 'この商品はすでに購入されています。');
            }

            $postcode = $request->input('postcode', $user->postcode);
            $address  = $request->input('address',  $user->address);
            $building = $request->input('building', $user->building);

            Purchase::create([
                'user_id'        => $user->id,
                'item_id'        => $lockedItem->id,
                'payment_method' => $request->input('payment_method'),
                'postcode'       => $postcode,
                'address'        => $address,
                'building'       => $building,
                'status'         => $status,
            ]);

            $lockedItem->update(['sold_at' => now()]);

            return redirect()
                ->route('items.index')
                ->with('status', '商品の購入が完了しました');
        });
    }

    public function editAddress(Item $item)
    {
        $user = Auth::user();

        return view('purchase.address', [
            'item' => $item,
            'user' => $user,
        ]);
    }

    public function updateAddress(AddressRequest $request, Item $item)
    {
        $user = $request->user();

        $user->update([
            'postcode' => $request->postcode,
            'address'  => $request->address,
            'building' => $request->building,
        ]);

        return redirect()
            ->route('purchase.show', $item)
            ->with('status', '住所を更新しました');
    }
}
