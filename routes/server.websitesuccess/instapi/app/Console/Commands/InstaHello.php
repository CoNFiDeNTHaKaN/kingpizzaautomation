<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Web64\Colors\Facades\Colors;
use Artisan;

class InstaHello extends Command
{
    protected $hidden = false;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gram:hello';

    /**
     * The console command description.
     *
     * @var string
     */
     protected $description = 'If you\'ve never used forevergram, start here';

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

      Colors::bg_light_blue('                          ');
      Colors::bg_light_blue__white('  Welcome to Forevergram  ');
      Colors::bg_light_blue('                          ');
      Colors::blue('- by Tom Knox.');
      $this->comment('Forevergram makes it easy to use tools like Instagrid.js with the V2.x Basic Display API by Instagram.');
      $this->comment('Forevergram receives account authorisation one-time and then provides long-life tokens forever.');
      $this->error('  Until Facebook change the API again 🤦🏻‍♂️  ');

      $this->comment(' ');
      Colors::bg_light_blue('What next?');
      $this->comment('There are a bunch of options Forevergram gives you to handle long-life tokens. To see a brief description of them run the command below and look for the section with the heading "gram"');
      $this->comment('./artisan list');




    }
}
