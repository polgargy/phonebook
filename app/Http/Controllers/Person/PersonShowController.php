<?php

namespace App\Http\Controllers\Person;

use Inertia\Response;
use App\Models\Person;

class PersonShowController extends PersonBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Person $person): Response
    {
        $person->load('emails', 'phones');

        return inertia('Person/Show', [
            'title'  => $person->full_name,
            'person' => $person,
        ]);
    }
}
