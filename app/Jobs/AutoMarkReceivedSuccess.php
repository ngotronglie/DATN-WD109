<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoMarkReceivedSuccess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120; // seconds

    public function __construct(public int $orderId)
    {
    }

    public function handle(): void
    {
        $order = Order::find($this->orderId);
        if (!$order) {
            return;
        }

        // Only mark as 'Đã giao thành công' if still delivered (5) and user hasn't confirmed
        if ((int)$order->status !== 5) {
            return;
        }
        if (!empty($order->received_confirmed_at)) {
            return;
        }

        // Mark as 'Đã giao thành công' (15)
        $order->status = 15;
        $order->save();
    }
}
