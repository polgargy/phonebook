<?php

namespace App\Rules;

use App\Models\Phone;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniquePhoneNumber implements ValidationRule
{
    private $personId;
    private const MESSAGE = 'Az országhívószám és telefonszám együtt már foglalt.';

    public function __construct($personId)
    {
        $this->personId = $personId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $countryCode = request()->input('phones.*.country_code');
        $number      = request()->input('phones.*.number');

        $foundPhone = Phone::where('country_code', $countryCode)
            ->where('number', $number)
            ->where('person_id', '!=', $this->personId)
            ->exists();

        if ($foundPhone) {
            $fail(self::MESSAGE);
        }
    }
}
