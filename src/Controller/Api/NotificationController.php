<?php

namespace App\Controller\Api;

use App\Message\AppNotification;
use App\Resource\NotificationListResource;
use App\Resource\NotificationResource;
use App\Service\ClientService;
use App\Service\NotificationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use OpenApi\Annotations as OA;

class NotificationController extends AbstractController
{
    public function __construct(
        protected NotificationService $notificationService,
        protected ClientService $clientService,
    ){

    }

    #[Route('/api/notifications', methods: 'GET')]

    /**
     * @OA\Get (
     *     tags={"Notifications"},
     *     description="Get paginated list of notifications",
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
     *     @OA\Parameter(
     *          name="clientId",
     *          description="Client ID",
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
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
        $data = $this->notificationService->paginate($request);

        $resource = new NotificationListResource($data);

        return $this->json($resource->collection());
    }

    #[Route('/api/notifications/{id}', methods: 'GET')]

    /**
     * @OA\Parameter(
     *     name="id",
     *     description="Displaying Notification",
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
     *      description="Notification not found",
     * ),
     * @OA\Response(
     *      response=401,
     *      description="Unauthorized",
     * ),
     * @OA\Tag(name="Notifications")
     */
    public function show(int $id): Response
    {
        $data = $this->notificationService->find($id);

        $resource = new NotificationResource($data);

        return $this->json($resource->make());
    }

    #[Route('/api/notifications', methods: 'POST')]

    /**
     * @OA\Post (
     *      tags={"Notifications"},
     *      description="Creating new notification",
     *      @OA\Parameter (
     *          name="clientId",
     *          description="Client ID",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="channel",
     *          description="Channel",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              example="sms"
     *          )
     *      ),
     *      @OA\Parameter (
     *          name="content",
     *          description="Content",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              example="Lorem Ipsum"
     *          )
     *      ),
     *      @OA\Response (
     *          response=201,
     *          description="Successfully Created",
     *      ),
     *      @OA\Response (
     *          response=422,
     *          description="Validation error",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      ),
     * )
     */
    public function store(Request $request, ValidatorInterface $validator, MessageBusInterface $bus): Response
    {
        $notification = $this->notificationService->fill($request);
        $errors = $validator->validate($notification);

        if (count($errors) > 0) {
            return $this->json(['msg' => $errors[0]->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $client = $this->clientService->find($notification->getClientId());

        if (!$client) {
            return $this->json(
                ['msg' => 'No client found for id '.$notification->getClientId()],
                Response::HTTP_NOT_FOUND
            );
        }

        $notification->setClient($client);
        $this->notificationService->save($notification, true);

        $bus->dispatch(new AppNotification($notification->getId()));

        return $this->json(['msg' => 'Notification is scheduled to be sent'], Response::HTTP_CREATED);
    }
}