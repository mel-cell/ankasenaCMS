<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['pemilu_id', 'user_id', 'kandidat_id'];
}
