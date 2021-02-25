<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstaSettings extends Command
{
    protected $hidden = false;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gram:settings';

    /**
     * The console command description.
     *
     * @var string
     */
     protected $description = 'Check and update settings for forevergram';

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
    public function handle()
    {
        //
    }
}
