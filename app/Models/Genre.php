<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    public $fillable =
        [
           'name'
        ];

    public function books()
    {
        return $this->hasMany('books','genreId', 'id');
    }
}
