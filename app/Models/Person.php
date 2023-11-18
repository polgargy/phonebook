<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $photo
 * @property string $zip
 * @property string $city
 * @property string $address
 * @property string $post_zip
 * @property string $post_city
 * @property string $post_address
 * @property string $created_at
 * @property string $updated_at
 * @property Email[] $emails
 * @property Phone[] $phones
 */
class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'photo',
        'zip',
        'city',
        'address',
        'post_zip',
        'post_city',
        'post_address',
    ];

    public function emails()
    {
        return $this->hasMany(Email::class);
    }

    public function phones()
    {
        return $this->hasMany(Phone::class);
    }
}
