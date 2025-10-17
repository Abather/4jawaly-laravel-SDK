<?php

namespace Sms4jawaly\Laravel;

use Illuminate\Notifications\Notification;

class Sms4JawalyChannel
{
    protected $client;

    public function __construct()
    {
        $this->client = app(Gateway::class);
    }

    public function send($notifiable, Notification $notification)
    {
        $receiver = $this->routeNotification($notifiable);

        $message = $notification->to4Jawaly($notifiable);

        if(is_string($message)){
            $message = Message::make($message);
        }

        if(!$message->getPhone()){
            $message->phone($receiver);
        }

        dd(...$message->toArray());
        $results = $this->client->sendSms(...$message->toArray());
        logger($results);
    }

    protected function routeNotification($notifiable)
    {
        $receiver = $notifiable->routeNotificationFor('sms-4-jawaly')
            ?? $notifiable->routeNotificationFor(Sms4JawalyChannel::class)
            ?? $notifiable->{config('services.sms4jawaly.receiver_attribute', 'phone')} ?? null;

        if (!$receiver) {
            return null;
        }

        if (is_string($receiver) && property_exists($notifiable, $receiver)) {
            return $notifiable->$receiver;
        }

        return $receiver;
    }
}