<?php

namespace App\Http\Service;

class DataMapperClient
{

    public function __construct(private array $data)
    {

    }

    public function asArray(): array
    {
        return [
            'last_name' => $this->data['lastName'],
            'first_name' => $this->data['firstName'],
            'middle_name' => $this->data['middleName']
        ];
    }
}
