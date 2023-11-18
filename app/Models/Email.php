<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $person_id
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @property Person $person
 */
class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'email',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
