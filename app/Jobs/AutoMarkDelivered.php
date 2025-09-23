<?php

namespace App\Jobs;

use App\Models\Order;
use App\Jobs\AutoMarkReceivedSuccess;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoMarkDelivered implements ShouldQueue
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

        // Only auto-complete if still in "Đang giao hàng" (status = 4)
        if ((int)$order->status !== 4) {
            return;
        }

        // Mark as delivered (5)
        $order->status = 5;

        // If COD not yet marked as collected, set collected
        if ((int)$order->status_method === 0) {
            $order->status_method = 1; // collected COD
        }
        if (!$order->payment_method) {
            $order->payment_method = 'cod';
        }

        // Stamp delivered_at when marking delivered
        if (empty($order->delivered_at)) {
            $order->delivered_at = now();
        }

        $order->save();

        // Schedule auto mark as 'Đã giao thành công' (15) after 3 days if not confirmed by user
        AutoMarkReceivedSuccess::dispatch($order->id)->delay(now()->addDays(3));
    }
}
