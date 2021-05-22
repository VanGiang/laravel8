<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\OrderStatistic;

class AdminController extends Controller
{
    public $viewData = [];

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orderData = OrderStatistic::orderBy('id', 'desc')
            ->limit(7)
            ->pluck('total_price', 'date')
            ->toArray();

        $daily = array_keys($orderData);
        $prices = array_values($orderData);

        $data = [
            'daily' => $daily,
            'prices' => $prices,
        ];

        return view('admins.index', $data);
    }
}
