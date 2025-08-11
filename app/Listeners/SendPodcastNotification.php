<?php

namespace App\Listeners;

use App\Events\PodcastProcessed;
use App\Mail\OrderConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendPodcastNotification
{

    /**
     * Handle the event.
     */
    public function handle(PodcastProcessed $event): void
    {
        $order = $event->order->load('user');
        if ($order->user && $order->user->email) {
            Mail::to($order->user->email)->send(new OrderConfirmation($order));
        }
    }
}
