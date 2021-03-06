<?php

namespace App\Http\Controllers;


use App\Events\NewNotification;
use App\Service\NotificationService;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * @var NotificationService
     */
    protected $notificationService;

    /**
     * NotificationsController constructor.
     *
     * @param NotificationService $notificationService
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function notificationCallback(Request $request)
    {
        $data = $request->toArray();

        if (!empty($data)) {
            $notification = $this->notificationService->create($data);
        }

        if ($request->isMethod('GET') && array_key_exists('hub_challenge', $data)) {
            return response($data['hub_challenge']);
        }

        broadcast(new NewNotification($notification));

        return response();
    }
}