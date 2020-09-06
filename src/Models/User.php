<?php
/*
 * Abstraction of the users table from the database
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    /**
     * @var array $fillable
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * @var array $hidden
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Defines the One-To-Many relationship with the journals table
     *
     * @return HasMany
     */
    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class, 'user_id', 'id');
    }

    /**
     * Defines the One-To-Many relationship with the images table
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'user_id', 'id');
    }
}
