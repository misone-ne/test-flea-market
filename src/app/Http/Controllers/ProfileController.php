<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // プロフィール編集画面表示
    public function edit()
    {
        $user = Auth::user();

        return view('mypage.edit', compact('user'));
    }

    /**
     * プロフィール更新処理
     *
     * - 既存のプロフィール画像がある場合は削除して差し替え
     * - 画像とそれ以外のデータを分離して更新し安全性を確保
     */
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        $validated = $request->except(['profile_image']);

        if ($request->hasFile('profile_image')) {

            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $path = $request->file('profile_image')->store('profile_images', 'public');
            $validated['profile_image'] = $path;
        }

        $user->update($validated);

        return redirect()->route('mypage');
    }

    /**
     * プロフィール画面表示
     *
     * - pageパラメータで表示内容を切替
     * 　（sell: ログインユーザーが出品した商品 / buy: ログインユーザーが購入した商品）
     * - 購入履歴の取得は中間テーブル（purchase）を使用
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            $items = Item::whereHas('purchase', function ($q) use ($user) {
                $q->where('buyer_id', $user->id);
            })->get();
        } else {
            $items = Item::where('user_id', $user->id)->get();
        }

        return view('mypage.index', compact('user', 'items', 'page'));
    }
}
