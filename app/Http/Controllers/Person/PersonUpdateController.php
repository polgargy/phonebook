<?php

namespace App\Http\Controllers\Person;

use App\Http\Requests\PersonRequest;
use App\Models\Person;
use App\Services\EmailService;
use Illuminate\Http\RedirectResponse;

class PersonUpdateController extends PersonBaseController
{
    public function __construct(private EmailService $emailService)
    {
    }

    public function __invoke(PersonRequest $request, Person $person): RedirectResponse
    {
        $data = $request->validated();

        unset($data['photo']);

        if ($data['delete_photo']) {
            $this->deletePhotoIfExists($person);
            $data['photo'] = null;
        }

        if ($request->hasFile('photo')) {
            $this->deletePhotoIfExists($person);
            $data['photo'] = $this->handlePhotoUpload($request->file('photo'));
        }

        $this->handlePostAddressData($data);
        $person->updateWithRelations($data, $this->emailService);

        return redirect()->route('persons.show', $request->person)->with('success', 'Sikeres módosítás!');
    }
}
