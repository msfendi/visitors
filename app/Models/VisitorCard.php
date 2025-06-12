<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'rfid',
        'visitor_code',
        'status_card',
        'void'
    ];
}
