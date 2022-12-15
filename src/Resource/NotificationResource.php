<?php

namespace App\Resource;

class NotificationResource extends JsonResource
{
    public function toArray()
    {
        return [
            'id' => $this->resource->getId(),
            'channel' => $this->resource->getChannel(),
            'content' => $this->resource->getContent(),
            'status' => $this->resource->getStatus(),
        ];
    }
}