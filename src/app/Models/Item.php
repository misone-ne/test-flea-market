<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'price',
        'description',
        'image_path',
        'condition',
        'is_sold',
    ];

    // 商品状態コードと表示用文字列の対応表
    const CONDITION_LABELS = [
        1 => '良好',
        2 => '目立った傷や汚れなし',
        3 => 'やや傷や汚れあり',
        4 => '状態が悪い',
    ];

    /**
     * 商品状態コードを表示用テキストへ変換
     *
     * - conditionに保存されている数値を、CONDITION_LABELSから取得して表示用文字列へ変換する
     */
    public function getConditionTextAttribute()
    {
        return self::CONDITION_LABELS[$this->condition] ?? '不明';
    }

    /**
     * 商品名キーワード検索
     *
     * - キーワード入力時のみ部分一致検索を適用
     */
    public function scopeKeywordSearch($query, ?string $keyword)
    {
        if ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favoriteItems()
    {
        return $this->belongsToMany(User::class, 'likes', 'item_id', 'user_id')->withTimestamps();
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }
}
