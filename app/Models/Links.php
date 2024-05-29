<?php

namespace App\Models;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Links extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'shortener_link';
    }
    public function getShortenerLinkAttribute($value)
    {
        return $this->attributes['shortener_link'] = url($value);
    }

    function logs()  {
        
        return $this->hasMany(LinksLogger::class, 'link_id', 'id');
    }    
    function user()  {
        
        return $this->hasOne(user::class, 'id', 'user_id');
    }
}
