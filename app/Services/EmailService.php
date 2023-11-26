<?php

namespace App\Services;

class EmailService
{
    public function formatEmailsData(array $emails): array
    {
        return array_map(fn ($email) => ['email' => $email], $emails);
    }
}
