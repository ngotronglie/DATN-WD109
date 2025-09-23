<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class BackfillDeliveredAt extends Command
{
    protected $signature = 'orders:backfill-delivered-at {--dry-run : Chỉ hiển thị số lượng sẽ cập nhật, không ghi vào DB}';

    protected $description = 'Điền giá trị delivered_at cho các đơn đã giao (status=5) còn trống, ưu tiên từ updated_at';

    public function handle(): int
    {
        $query = Order::where('status', 5)
            ->whereNull('delivered_at');

        $count = (clone $query)->count();
        if ($count === 0) {
            $this->info('Không có đơn cần backfill.');
            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->info('Số đơn sẽ được backfill: ' . $count);
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $updated = 0;
        $query->chunkById(200, function ($orders) use (&$updated, $bar) {
            foreach ($orders as $order) {
                // Ưu tiên lấy thời điểm giao từ updated_at nếu hợp lệ, fallback now()
                $order->delivered_at = $order->updated_at ?? now();
                $order->save();
                $updated++;
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info("Đã backfill delivered_at cho {$updated} đơn hàng.");

        return self::SUCCESS;
    }
}
