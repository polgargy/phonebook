<?php

namespace App\Http\Controllers\Person;

use App\Http\Requests\PersonRequest;
use App\Models\Person;
use App\Services\EmailService;
use Illuminate\Http\RedirectResponse;

class PersonStoreController extends PersonBaseController
{
    public function __construct(private EmailService $emailService)
    {
    }

    public function __invoke(PersonRequest $request, Person $person): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->handlePhotoUpload($request->file('photo'));
        }

        $this->handlePostAddressData($data);
        $person->createWithRelations($data, $this->emailService);

        return redirect()->route('persons.index')->with('success', 'Sikeres mentés!');
    }
}
