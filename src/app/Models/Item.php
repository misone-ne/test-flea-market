<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'brand',
        'price',
        'description',
        'image_path',
        'condition',
        'is_sold',
    ];

    const CONDITION_LABELS = [
        1 => '良好',
        2 => '目立った傷や汚れなし',
        3 => 'やや傷や汚れあり',
        4 => '状態が悪い',
    ];

    // 表示用のテキスト取得
    public function getConditionTextAttribute()
    {
        return self::CONDITION_LABELS[$this->condition] ?? '不明';
    }
}
