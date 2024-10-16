<?php

namespace MKD\FastAPI\commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use MKD\FastAPI\FastAPIService;

class FastAPICacheCommand extends Command
{
    // The name and signature of the console command
    protected $signature = 'fast-api:cache';

    // The console command description
    protected $description = 'Cache Fast-api Controllers';



    // Execute the console command
    public function handle()
    {
        $services = new FastAPIService();
        Cache::put('fast-api-controllers', $services->getControllers());
        $this->info('FastAPI Controllers cached successfully!');

    }
}
