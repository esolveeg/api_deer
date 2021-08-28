<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
  protected $guarded = [];
  public function scopeTop($query)
    {
        return $query->where('topAuthor', true);
    }
}
