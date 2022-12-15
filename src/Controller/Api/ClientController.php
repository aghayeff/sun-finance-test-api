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
use OpenApi\Annotations as OA;

class ClientController extends AbstractController
{
    public function __construct(
        protected ClientService $clientService
    ){

    }

    #[Route('/api/private/clients', methods: 'GET')]

    /**
     * @OA\Get (
     *     tags={"Clients"},
     *     description="Get paginated list of clients",
     *     @OA\Parameter(
     *          name="page",
     *          description="Page Id for pagination",
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *              example=1
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="per_page",
     *          description="Paginated data count",
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *              example=10
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      )
     * )
     */
    public function index(Request $request): Response
    {
        $data = $this->clientService->paginate($request);

        $resource = new ClientListResource($data);

        return $this->json($resource->collection());
    }

    #[Route('/api/clients/{id}', methods: 'GET')]

    /**
     * @OA\Parameter(
     *     name="id",
     *     description="Displaying Client",
     *     in="path",
     *     @OA\Schema(
     *          type="integer"
     *     )
     * )
     * @OA\Response(
     *      response=200,
     *      description="Success",
     * ),
     * @OA\Response(
     *      response=404,
     *      description="Client not found",
     * ),
     * @OA\Tag(name="Clients")
     */
    public function show(int $id): Response
    {
        $data = $this->clientService->find($id);

        $resource = new ClientResource($data);

        return $this->json($resource->make());
    }

    #[Route('/api/clients', methods: 'POST')]

    /**
     * @OA\Post (
     *      tags={"Clients"},
     *      description="Creating new client",
     *      @OA\Parameter (
     *          name="firstName",
     *          description="First Name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="lastName",
     *          description="Last Name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="email",
     *          description="Email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="phoneNumber",
     *          description="Phone number compatible E.164",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response (
     *          response=201,
     *          description="Successfully Created",
     *      ),
     *      @OA\Response (
     *          response=422,
     *          description="Validation error",
     *      )
     * )
     */
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

    #[Route('/api/clients/{id}', methods: 'PUT')]

    /**
     * @OA\Put (
     *      tags={"Clients"},
     *      description="Updating client by id",
     *      @OA\Parameter (
     *          name="id",
     *          description="Client ID",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="firstName",
     *          description="First Name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="lastName",
     *          description="Last Name",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="email",
     *          description="Email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="phoneNumber",
     *          description="Phone number compatible E.164",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response (
     *          response=201,
     *          description="Successfully Created",
     *      ),
     *      @OA\Response (
     *          response=422,
     *          description="Validation error",
     *      )
     * )
     */
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

    #[Route('/api/clients/{id}', methods: 'DELETE')]

    /**
     * @OA\Delete (
     *      tags={"Clients"},
     *      description="Deleting client by id",
     *      @OA\Parameter (
     *          name="id",
     *          description="Client ID",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response (
     *          response=200,
     *          description="Successfully Deleted",
     *      ),
     *      @OA\Response (
     *          response=422,
     *          description="Client not found",
     *      )
     * )
     */
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