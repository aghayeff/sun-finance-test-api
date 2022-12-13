<?php

namespace App\Controller\Api;

use App\Resource\ClientListResource;
use App\Resource\ClientResource;
use App\Service\ClientService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ClientController extends AbstractController
{
    public function __construct(
        protected ClientService $clientService
    ){

    }

    #[Route('/api/clients', methods: 'GET')]
    public function index(Request $request): Response
    {
        $data = $this->clientService->paginate($request);

        $resource = new ClientListResource($data);

        return $this->json($resource->collection());
    }

    #[Route('/api/client/{id}', methods: 'GET')]
    public function show(int $id): Response
    {
        $data = $this->clientService->find($id);

        $resource = new ClientResource($data);

        return $this->json($resource->make());
    }

    #[Route('/api/client', methods: 'POST')]
    public function store(Request $request, ValidatorInterface $validator): Response
    {
        $client = $this->clientService->fill($request);

        $errors = $validator->validate($client);

        if (count($errors) > 0) {
            return $this->json(['msg' => $errors[0]->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->clientService->save($client, true);

        return $this->json(['msg' => 'Client created'], Response::HTTP_CREATED);
    }

    #[Route('/api/client/{id}', methods: 'POST')]
    public function update(Request $request, ValidatorInterface $validator, int $id): Response
    {
        $client = $this->clientService->find($id);

        if (!$client) {
            return $this->json(['msg' => 'No client found for id '.$id], Response::HTTP_NOT_FOUND);
        }

        $client = $this->clientService
            ->setEntity($client)
            ->fill($request);

        $errors = $validator->validate($client);

        if (count($errors) > 0) {
            return $this->json(['msg' => $errors[0]->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->clientService->save($client, true);

        return $this->json(['msg' => 'Client updated'], Response::HTTP_CREATED);
    }

    #[Route('/api/client/{id}', methods: 'DELETE')]
    public function delete(int $id): Response
    {
        $client = $this->clientService->find($id);

        if (!$client) {
            return $this->json(['msg' => 'No client found for id '.$id], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->clientService->remove($client, true);

        return $this->json(['msg' => 'Client removed']);
    }
}