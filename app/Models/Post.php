<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function category(){
        return $this->BelongsTo(Category::class);
    }

    public function tag(){
        return $this->BelongsTo(Tag::class);
    }

    protected $guarded = [];

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'tag_id'
    ];
}
