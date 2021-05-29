<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatistic;

class OrderService extends BaseService
{
    protected $orderModel;
    protected $orderStatisticModel;

    public function __construct(Order $order, OrderStatistic $orderStatistic)
    {
        $this->orderModel = $order;
        $this->orderStatisticModel = $orderStatistic;
    }

    /**
     * Get total order price by date
     *
     * @return int $price
    */
    public function getTotalPrice()
    {
        $today = now()->format('Y-m-d');
        $yesterday = now()->subDays()->format('Y-m-d');

        \DB::enableQueryLog();
        $price = $this->orderModel
            ->where('created_at', '<', $today)
            ->where('created_at', '>', $yesterday)
            ->sum('total_price');

        \Log::info(\DB::getQueryLog());

        return $price;
    }

    public function createDataStatistic()
    {
        $totalPrice = $this->getTotalPrice();
        $yesterday = now()->subDays();

        $data = [
            'date' => $yesterday,
            'total_price' => $totalPrice,
            'total_order' => 1,
        ];

        try {
            $this->orderStatisticModel->create($data);
        } catch (\Throwable $th) {
            \Log::error($th);

            return false;
        }

        return true;
    }
}
