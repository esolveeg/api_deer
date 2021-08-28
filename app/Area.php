<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('apply' , true);
    }

    public function scopeMain($query)
    {
        return $query->where('sectionId' , null);
    }
    public function children()
    {
        return $this->hasMany(self::class, 'sectionId');
    }
    public function parent()
    {
        return $this->belongsTo(self::class, 'sectionId');
    }

    public function grandchildren()
    {
        return $this->children()->with('grandchildren');
    }



}
