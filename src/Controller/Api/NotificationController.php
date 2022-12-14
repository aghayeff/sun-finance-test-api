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
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Messenger\MessageBusInterface;

class NotificationController extends AbstractController
{
    public function __construct(
        protected NotificationService $notificationService,
        protected ClientService $clientService,
    ){

    }

    #[Route('/api/notifications', methods: 'GET')]
    public function index(Request $request): Response
    {
        $data = $this->notificationService->paginate($request);

        $resource = new NotificationListResource($data);

        return $this->json($resource->collection());
    }

    #[Route('/api/notification/{id}', methods: 'GET')]
    public function show(int $id): Response
    {
        $data = $this->notificationService->find($id);

        $resource = new NotificationResource($data);

        return $this->json($resource->make());
    }

    #[Route('/api/notification', methods: 'POST')]
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