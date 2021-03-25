<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Call
 *
 * @property int $id
 * @property int $phone
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Call newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Call newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Call query()
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Call whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Call extends Model
{
    use HasFactory;
    protected $fillable = [
      'phone',
      'name',
    ];
}
