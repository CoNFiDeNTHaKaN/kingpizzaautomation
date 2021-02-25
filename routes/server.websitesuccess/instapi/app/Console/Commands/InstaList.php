<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstaList extends Command
{
    protected $hidden = false;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gram:list';

    /**
     * The console command description.
     *
     * @var string
     */
     protected $description = 'List all currently renewable accounts, their tokens, and the time left till renewal';

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
