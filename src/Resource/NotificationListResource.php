<?php

namespace App\Resource;

class NotificationListResource extends JsonResource
{
    public function toArray()
    {
        $clientResource = new ClientResource($this->resource->getClient());

        return [
            'id' => $this->resource->getId(),
            'client' => $clientResource->setWithBody(false)->make(),
            'channel' => $this->resource->getChannel(),
            'content' => $this->resource->getContent(),
            'status' => $this->resource->getStatus(),
        ];
    }
}