<?php
/*
 * Abstraction of the journals table from the database
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Journal extends Model
{
    /**
     * @var array $fillable
     */
    protected $fillable = [
        'name',
        'body',
        'user_id',
    ];

    /**
     * Defines the Many-To-One relationship with the users table
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Defines the One-To-Many relationship with the images table
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'journal_id', 'id');
    }
}
