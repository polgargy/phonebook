<?php

namespace App\Http\Requests;

use App\Rules\UniquePhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $personId = $this->route('person')->id ?? null;

        return [
            'photo'                  => ['nullable', 'image', 'max:5120'],
            'delete_photo'           => ['required', 'boolean'],
            'last_name'              => ['required', 'string', 'max:255'],
            'first_name'             => ['required', 'string', 'max:255'],
            'emails'                 => ['required', 'array'],
            'emails.*'               => ['required', 'email', 'max:255', Rule::unique('emails', 'email')->ignore($personId, 'person_id'), 'distinct'],
            'phones'                 => ['array'],
            'phones.*.country_code'  => ['nullable', 'numeric', 'digits_between:1,4', 'required_if:phones.*.number,null'],
            'phones.*.number'        => ['nullable', 'numeric', 'digits_between:8,12', new UniquePhoneNumber($personId), 'distinct', 'required_if:phones.*.country_code,null'],
            'zip'                    => ['nullable', 'numeric', 'digits:4'],
            'city'                   => ['nullable', 'string', 'max:255'],
            'address'                => ['nullable', 'string', 'max:255'],
            'post_zip'               => ['nullable', 'numeric', 'digits:4'],
            'post_city'              => ['nullable', 'string', 'max:255'],
            'post_address'           => ['nullable', 'string', 'max:255'],
            'different_post_address' => ['required', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'photo'                  => 'fénykép',
            'delete_photo'           => 'fénykép törlése',
            'last_name'              => 'vezetéknév',
            'first_name'             => 'keresztnév',
            'emails.*'               => 'email',
            'phones.*.country_code'  => 'országhívószám',
            'phones.*.number'        => 'telefonszám',
            'zip'                    => 'irányítószám',
            'city'                   => 'város',
            'address'                => 'cím',
            'post_zip'               => 'levelezési irányítószám',
            'post_city'              => 'levelezési város',
            'post_address'           => 'levelezési cím',
            'different_post_address' => 'levelezési cím eltér a lakcímtől',
        ];
    }
}
