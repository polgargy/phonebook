<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PersonBaseController extends Controller
{
    protected function handlePhotoUpload(UploadedFile $file): ?string
    {
        $name = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs('photos', $name, 'public');
    }

    protected function deletePhotoIfExists(Person $person): void
    {
        if (!$person->photo) {
            return;
        }

        Storage::delete('public/' . $person->photo);
    }

    protected function handlePostAddressData(array &$data): void
    {
        if ($data['different_post_address']) {
            return;
        }

        $data['post_zip']     = $data['zip'];
        $data['post_city']    = $data['city'];
        $data['post_address'] = $data['address'];
    }
}
