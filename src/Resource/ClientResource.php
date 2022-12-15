<?php

namespace App\Resource;

class ClientResource extends JsonResource
{
    public function toArray()
    {
        return [
            'id' => $this->resource->getId(),
            'firstName' => $this->resource->getFirstName(),
            'lastName' => $this->resource->getLastName(),
            'email' => $this->resource->getEmail(),
            'phoneNumber' => $this->resource->getPhoneNumber(),
        ];
    }
}