<?php

namespace App\Http\Controllers\Person;

use Inertia\Response;
use App\Models\Person;

class PersonIndexController extends PersonBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return inertia('Person/Index', [
            'title'   => 'Személyek listája',
            'persons' => Person::orderBy('last_name')->orderBy('first_name')->get(),
        ]);
    }
}
