<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Auction extends Model
{
    use HasFactory, HasSlug;

    const TYPE_TEXT = 'text';

    protected $fillable = ['user_id', 'image', 'title', 'slug', 'status', 'description', 'expire_date'];
    
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
    public function conditions()
    {
        return $this->hasMany(AuctionCondition::class);
    }
}
