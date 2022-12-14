<?php

namespace App\Resource;

class NotificationResource extends JsonResource
{
    public function toArray()
    {
        return [
            'channel' => $this->resource->getChannel(),
            'content' => $this->resource->getContent(),
            'status' => $this->resource->getStatus(),
        ];
    }
}