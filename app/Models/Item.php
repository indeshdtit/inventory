<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name','is_selected'
    ];
   
    protected $casts = [
        'item_name' => 'string',
        'is_selected' => 'boolean'
    ];
}
