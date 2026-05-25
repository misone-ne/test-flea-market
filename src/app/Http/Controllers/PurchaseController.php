<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    /**
     * 商品購入画面表示
     * 
     * - 商品情報とログインユーザー情報を取得 → 商品購入画面の初期表示データとして使用
     */
    public function show(Request $request, int $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $payment_method = $request->query('payment_method');

        return view('products.purchase', compact(
            'item',
            'user',
            'payment_method'
        ));
    }

    /**
     * 小計画面更新
     * 
     * 支払い方法変更時に画面を再描画し、小計画面へ選択内容を反映
     * ※DB更新は行わず、画面表示のみ更新
     */
    public function preview(Request $request, int $item_id)
    {
        $validated = $request->validate([
            'payment_method' => 'nullable|in:コンビニ支払い,カード支払い',
        ]);

        return redirect()
            ->route('purchase.show', [
                'item_id' => $item_id,
                'payment_method' => $validated['payment_method'],
            ]);
    }

    // 住所変更ページ表示
    public function editAddress(int $item_id)
    {
        $user = Auth::user();

        return view('mypage.edit-address', compact('item_id', 'user'));
    }

    /**
     * 住所更新処理
     * 
     * データを保存し、購入画面へ戻す
     */
    public function updateAddress(AddressRequest $request, int $item_id)
    {
        $user = Auth::user();
        $user->update($request->validated());

        return redirect()->route('purchase.show', ['item_id' => $item_id]);
    }

    /**
     * 商品購入処理
     * 
     * - 売切れ状態を確認（二重購入防止）
     * - 購入履歴を保存
     * - 商品を売却済へ更新
     * - 選択された支払い方法に応じてStripe決済方法切替
     */
    public function store(PurchaseRequest $request, int $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        if ($item->is_sold) {
            return back()->with('error', 'この商品はすでに売り切れています');
        }

        $validated = $request->validated();

        Purchase::create([
            'item_id' => $item->id,
            'buyer_id' => $user->id,
            'payment_method' => $validated['payment_method'],
            'post_code' => $validated['post_code'],
            'address' => $validated['address'],
            'building' => $validated['building'],
        ]);

        $item->update([
            'is_sold' => true,
        ]);

        // 以下、Stripe決済セッション作成

        $paymentMethods =
            $validated['payment_method'] === 'カード支払い'
            ? ['card']
            : ['konbini'];

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => $paymentMethods,

            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],

            'mode' => 'payment',

            'success_url' => route('index'),
            'cancel_url' => route('purchase.show', ['item_id' => $item->id]),
        ]);

        return redirect($session->url);
    }
}
