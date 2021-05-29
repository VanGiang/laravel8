<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OrderService;

class OrderStatistic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Order Statistic';
    protected $orderService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OrderService $orderService)
    {
        parent::__construct();

        $this->orderService = $orderService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $flg = $this->orderService->createDataStatistic();

        if ($flg) {
            $this->info('Run batch success.');
        } else {
            $this->info('Run batch failed.');
        }

        return 0;
    }
}
