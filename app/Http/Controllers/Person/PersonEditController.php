<?php

namespace App\Http\Controllers\Person;

use Inertia\Response;
use App\Models\Person;

class PersonEditController extends PersonBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Person $person): Response
    {
        $person->load('emails', 'phones');

        return inertia('Person/Form', [
            'title'  => "$person->full_name adatainak módosítása",
            'person' => $person,
        ]);
    }
}
