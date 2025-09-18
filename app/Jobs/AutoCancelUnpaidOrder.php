<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\Voucher;

class AutoCancelUnpaidOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $orderId;

    public int $tries = 1;
    public int $timeout = 30;

    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = Order::with(['orderDetails.productVariant'])->find($this->orderId);
        if (!$order) {
            return;
        }

        // Nếu đã thanh toán (online) hoặc đã xác nhận thì không hủy
        if ((int) $order->status_method === 2 || (int) $order->status === 1) {
            return;
        }

        // Hủy đơn: status = 6, giữ status_method = 0
        $order->status = 6;
        $order->save();

        // Khôi phục tồn kho và voucher
        foreach ($order->orderDetails as $detail) {
            if ($detail->productVariant) {
                $detail->productVariant->increment('quantity', (int) $detail->quantity);
            }
        }

        if (!empty($order->voucher_id)) {
            $voucher = Voucher::find($order->voucher_id);
            if ($voucher) {
                $voucher->increment('quantity', 1);
            }
        }
    }
}
