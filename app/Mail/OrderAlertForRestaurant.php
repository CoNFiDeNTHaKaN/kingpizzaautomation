<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderAlertForRestaurant extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $user;
    public $basket;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order, $user, $basket)
    {
        $this->order = $order;
        $this->user = $user;
        $this->basket = $basket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.order-alert-for-restaurant');
    }
}
