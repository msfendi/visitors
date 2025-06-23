<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'rfid',
        'visitor_code', // V0000001
        'visitor_number', //iteration number
        'status_card',
        'qr_name',
        'qr_path',
        'void'
    ];
}
