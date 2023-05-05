<?php

namespace App\Http\Service;

class DataMapper
{
    public function rename(array $name): array
    {
        $result = [
            'last_name' => $name['lastName'],
            'first_name' => $name['firstName'],
            'middle_name' => $name['middleName']
        ];

        return $result;
    }
}
