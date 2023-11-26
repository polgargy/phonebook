<?php

namespace App\Models;

use App\Services\EmailService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
 * @property object $updated_at
 * @property Collection|Email[] $emails
 * @property Collection|Phone[] $phones
 * @property string $full_name
 * @property string $full_address
 * @property string $full_post_address
 * @property string $photo_path
 * @property array $emails_array
 * @property array $phones_array
 */
class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';

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

    protected $appends = ['full_address', 'full_post_address', 'photo_path', 'emails_array', 'phones_array'];

    public function emails(): HasMany
    {
        return $this->hasMany(Email::class);
    }

    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    public function getFullAddressAttribute(): string
    {
        return "$this->zip $this->city, $this->address";
    }

    public function getFullPostAddressAttribute(): string
    {
        return "$this->post_zip $this->post_city, $this->post_address";
    }

    public function getPhotoPathAttribute(): string
    {
        return $this->photo ? Storage::url($this->photo) : '';
    }

    public function getEmailsArrayAttribute(): array
    {
        return $this->emails->map(function ($email) {
            return $email->email;
        })->toArray();
    }

    public function getPhonesArrayAttribute(): array
    {
        return $this->phones->map(function ($phone) {
            return "+$phone->country_code $phone->number";
        })->toArray();
    }

    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)->setTimezone('CET')->format('Y.m.d. H:i:s');
    }

    public function createWithRelations(array $data, EmailService $emailService): self
    {
        return DB::transaction(function () use ($data, $emailService) {
            $person = self::create($data);

            $person->emails()->createMany($emailService->formatEmailsData($data['emails']));
            $person->phones()->createMany($data['phones']);

            return $person;
        }, 5);
    }

    public function updateWithRelations(array $data, EmailService $emailService): void
    {
        DB::transaction(function () use ($data, $emailService) {
            $this->update($data);

            $this->updateEmails($emailService->formatEmailsData($data['emails']));
            $this->updatePhones($data['phones']);
        }, 5);
    }

    private function updateEmails(array $emailsData): void
    {
        foreach ($emailsData as $email) {
            $this->emails()->firstOrNew($email)->save();
        }

        // Remove any emails not in the request
        $this->emails()->whereNotIn('email', $emailsData)->delete();
    }

    private function updatePhones(array $phonesData): void
    {
        foreach ($phonesData as $phone) {
            $this->phones()->firstOrNew($phone)->save();
        }

        // Remove any phones not in the request
        $phonesData = collect($phonesData)
            ->map(fn ($phone) => $phone['country_code'] . '-' . $phone['number'])
            ->toArray();

        $this->phones()->whereNotIn(DB::raw('CONCAT(country_code, "-", number)'), $phonesData)->delete();
    }
}
