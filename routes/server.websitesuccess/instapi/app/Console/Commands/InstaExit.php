<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Web64\Colors\Facades\Colors;

class InstaExit extends Command
{
    protected $hidden = true;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gram:exit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        Colors::rainbow('Thanks for dropping by.');
    }
}
