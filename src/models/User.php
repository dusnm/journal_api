<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class, 'user_id', 'id');
    }
}
