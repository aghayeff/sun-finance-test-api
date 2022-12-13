<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class EntityService
{
    public function __construct(
        protected mixed $entity,
        protected mixed $repository
    ) {
    }

    public function fill(Request $request)
    {
        $payload = $this->payload($request);

        foreach ($payload as $property => $value) {
            $method = "set{$property}";
            $this->entity->$method($value);
        }

        return $this->entity;
    }

    public function payload(Request $request): array
    {
        return $request->request->all();
    }

    public function setEntity($entity): EntityService
    {
        $this->entity = $entity;

        return $this;
    }

    public function paginate(Request $request, int $limit = 10)
    {
        $offset = $limit * ($request->get('page', 1) - 1);

        return $this->repository->paginate($limit, $offset, $request);
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    public function save($entity, bool $flush = false)
    {
        return $this->repository->save($entity, $flush);
    }

    public function remove($entity, bool $flush = false)
    {
        return $this->repository->remove($entity, $flush);
    }
}