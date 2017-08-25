<?php

namespace App\Console\Commands;

use App\Http\Controllers\Test\QueueController;
use Illuminate\Console\Command;

class Brpop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:brpop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test redis brpop';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(QueueController $queueController)
    {
        while($res = $queueController->redisListBrpop()) {
            var_dump($res);
        }
    }
}
