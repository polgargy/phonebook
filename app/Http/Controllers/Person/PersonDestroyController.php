<?php

namespace App\Http\Controllers\Person;

use App\Models\Person;
use Illuminate\Http\JsonResponse;

class PersonDestroyController extends PersonBaseController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Person $person): JsonResponse
    {
        $this->deletePhotoIfExists($person);

        $person->delete();
        return response()->json(['success' => 'Sikeres törlés!']);
    }
}
