<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeType extends Model
{
    use HasFactory;
    protected $fillable = [
        "scheme_type_name",
        "status"
    ];
    protected $casts = [
        'status' => StatusEnum::class
    ];
}
