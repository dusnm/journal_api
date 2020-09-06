<?php
/*
 * Abstraction of the images table from the database
 *
 * @author Dusan Mitrovic <dusan@dusanmitrovic.xyz>
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    /**
     * @var array $fillable
     */
    protected $fillable = ['url', 'user_id', 'journal_id'];

    /**
     * @var array $hidden
     */
    protected $hidden = ['user_id', 'journal_id'];

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
     * Defines the Many-To-One relationship with the journals table
     *
     * @return BelongsTo
     */
    public function journal(): BelongsTo
    {
        return $this->belongsTo(Journal::class, 'journal_id', 'id');
    }
}
