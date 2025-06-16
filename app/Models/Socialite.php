<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Socialite extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'socialites';

    protected $fillable = [
        'user_id',
        'provider_id',
        'provider_name',
        'provider_token',
        'provider_refresh_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
