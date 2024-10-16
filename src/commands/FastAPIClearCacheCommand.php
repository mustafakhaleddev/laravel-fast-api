<?php

namespace MKD\FastAPI\commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use MKD\FastAPI\FastAPIService;

class FastAPIClearCacheCommand extends Command
{
    // The name and signature of the console command
    protected $signature = 'fast-api:clear-cache';

    // The console command description
    protected $description = 'Clear Fast-api Controllers Cache';


    // Execute the console command
    public function handle()
    {
        Cache::forget('fast-api-controllers');
        $this->info('FastAPI routes cache cleared!');
    }
}
