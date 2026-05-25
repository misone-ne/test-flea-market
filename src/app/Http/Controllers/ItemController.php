<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    /**
     * 商品一覧画面表示
     * 
     * - keyword: 商品名検索
     * - tabパラメータで表示内容を切替
     * 　（recommend: おすすめ商品一覧 / mylist: いいね済商品一覧）
     * - ログインユーザーが出品した商品は一覧から除外
     */
    public function index(Request $request)
    {
        $keyword = $request->query('keyword');
        $tab = $request->query('tab', 'recommend');
        $user = Auth::user();

        if ($tab === 'mylist') {
            $items = $user
                ? $user->favoriteItems()
                ->keywordSearch($keyword)
                ->latest()
                ->get()
                : collect();
        } else {
            $query = Item::query();
            if ($user) {
                $query->where('user_id', '!=', $user->id);
            }
            $items = $query
                ->keywordSearch($keyword)
                ->latest()
                ->get();
        }

        return view('products.index', compact('items', 'tab', 'keyword'));
    }

    /**
     * 商品詳細画面表示
     * 
     * - 商品情報 + リレーション（user / categories / comments.user）
     * - 件数取得（いいね数・コメント数）
     * - ログインユーザーの いいね 状態を判定し、UIを切り替えるために使用
     */
    public function show(int $item_id)
    {
        $item = Item::with(['user', 'categories', 'comments.user'])
            ->withCount(['favoriteItems', 'comments'])
            ->findOrFail($item_id);

        $user = Auth::user();

        $isLiked = $user
            ? $user->favoriteItems()->where('item_id', $item->id)->exists()
            : false;

        return view('products.item', compact('item', 'isLiked'));
    }

    /**
     * いいね トグル処理
     * 
     * 中間テーブル（likes）を使用し、
     * ユーザーの状態に応じて attach / detach を切替
     */
    public function toggleLike(int $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        if ($user->favoriteItems()->where('item_id', $item_id)->exists()) {
            $user->favoriteItems()->detach($item_id);
        } else {
            $user->favoriteItems()->attach($item_id);
        }

        return back();
    }

    /**
     * コメント投稿処理
     * 
     * - ログインユーザーのみ投稿可能
     */
    public function storeComment(CommentRequest $request, int $item_id)
    {
        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'comment' => $request->validated()['comment'],
        ]);

        return redirect()->route('item.show', ['item_id' => $item_id]);
    }

    /**
     * 商品出品画面表示
     * 
     * - カテゴリー一覧を取得してチェックボックス表示用に渡す
     */
    public function sell()
    {
        $categories = Category::all();

        return view('products.sell', compact('categories'));
    }

    /**
     * 商品出品処理
     * 
     * - 画像はstorage/publicに保存
     * - 商品登録後、カテゴリを中間テーブル（category_item）に紐付け
     */
    public function store(ExhibitionRequest $request)
    {
        $imagePath = $request->file('image')->store('item_images', 'public');

        $item = Item::create([
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'brand'       => $request->brand,
            'price'       => $request->price,
            'description' => $request->description,
            'image_path'  => $imagePath,
            'condition'   => $request->condition,
            'is_sold'     => false,
        ]);

        $item->categories()->attach($request->category_ids);

        return redirect()->route('index');
    }
}
