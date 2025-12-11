<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    public $str;
    protected $table = 'books';
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id'); //У одной книги одна категория
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'book_tags', 'book_id', 'tag_id'); //У одной книги несколько тэгов
    }
}
