<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\StatusEnum;

class Scheme extends Model
{
    use HasFactory;
    protected $fillable = [
        "scheme_type_id",
        "total_amount",
        "amount_per_month",
        "no_of_months",
        "coins",
        "status"
    ];   
    protected $casts = [
        'status' => StatusEnum::class
    ];
}
