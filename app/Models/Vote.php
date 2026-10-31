<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['pemilu_id', 'user_id', 'kandidat_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kandidat()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function pemilu()
    {
        return $this->belongsTo(Election::class);
    }
}
