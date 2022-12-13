<?php

namespace App\Resource;


class JsonResource
{
    public mixed $entity;
    public mixed $resource;
    public int $status;
    public bool $withBody = true;

    public function __construct($entity, int $status = 200)
    {
        $this->entity = $entity;
        $this->status = $status;
        $this->withBody = true;
    }

    /**
     * Create a new resource instance.
     */

    public function setWithBody(bool $value): static
    {
        $this->withBody = $value;
        return $this;
    }

    public function make()
    {
        $this->resource = $this->entity;
        $data = $this->resource ? $this->toArray() : [];

        if (!$this->withBody) {
            return $data;
        }
        return [
            'data' => $data,
            'status' => $this->resource ? $this->status : 404
        ];
    }

    public function collection()
    {
        $data = [];

        foreach ($this->entity as $resource) {
            $this->resource = $resource;
            $data[] = $this->toArray();
        }

        return [
            'data' => $data,
            'status' => $this->status
        ];
    }
}