<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'visit_date',
        'visit_time',
        'leave_time',
        'purpose',
        'appointer',
        'dept',
        'security_id',
        'visitor_card_id',
    ];
}
