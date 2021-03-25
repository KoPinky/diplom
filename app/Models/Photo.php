<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Photo
 *
 * @package App\Models
 * @property-read \App\Models\User|null $photo
 * @method static \Illuminate\Database\Eloquent\Builder|Photo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Photo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Photo query()
 * @mixin \Eloquent
 */
class Photo extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'path',
        'mime',
        'size',
    ];

    public function photo(): HasOne
    {
        return $this->hasOne(User::class, 'photo', 'id');
    }
}
