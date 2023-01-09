<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionCondition extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'condition', 'type', 'auction_id', 'data'];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
}
