<?php

namespace App\Http\Controllers\Person;

use Inertia\Response;

class PersonCreateController extends PersonBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return inertia('Person/Form', [
            'title'  => 'Új személy hozzáadása',
            'person' => null,
        ]);
    }
}
