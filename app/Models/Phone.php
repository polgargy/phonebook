<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $person_id
 * @property integer $country_code
 * @property integer $number
 * @property string $created_at
 * @property string $updated_at
 * @property Person $person
 */
class Phone extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'country_code',
        'number',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
