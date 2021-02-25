<?php

namespace App\Console\Commands;

use App\Account;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class InstaAuth extends Command
{
    protected $hidden = false;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gram:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new account for renewal, requires an initial token, provides instructions';

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
        //check if user has secured an initial token
        $userHasInitialToken = $this->confirm('Have you got an initial long-life token to start with? (\'No\' will provide instructions on how to get one)');
        // if no, offer instructions
        if (!$userHasInitialToken) {
          $this->error('You have to have an initial long-life token to setup for renewal');
          $this->comment('Help: To get a long-life token you essentially need to create a Facebook app on the developer platform and then add the target Instagram account as an "Instagram tester" for the app. Then you can access an initial long-life token. Detailed instructions are below. Once you\'ve got the first token come back and run gram:auth again.');
          $this->comment('① Go to "facebook for developers" and create a new app - https://developers.facebook.com/apps/ - NOTE: if prompted for "How are you using your app?" you must now specify "For Everything Else"' . "\n");
          $this->comment('② Scroll down to "Add a product" and add "Instagram". This should be almost instant. Once completed, in the sidebar, click Products > Basic Display and then at the bottom of the page click Create New App to link this facebook app to Instagram.' . "\n");
          $this->comment('③ Back to the sidebar now, click Roles > Test Users and on that page click Add Instagram Testers under the Instagram Testers heading at the bottom of the page. When the modal opens add the instagram account to access by its Instagram username. The account must be public.' . "\n");
          $this->comment('④ Now you need to authorise the facebook app to access the instagram account. Sign in on a computer (not a mobile app), then go to "account settings", "apps and websites" and under the tab labelled "Tester Invites" hit "Accept".' . "\n");
          $this->comment('⑤ Back on facebook for developers. Go into your app, click Products > Basic Display and scroll down to "User Token Generator". You should now see the user you just added. Click the "Generate Token" button and sign in if required. The intial long-lived token will then appear. Copy it to somewhere safe.' . "\n");
          $this->comment('Easy as that :\'( come back here and rerun gram:auth to put it to use.');
          die();
        } else {
          $userToken = $this->ask('Please paste the initial long-lived token here');
        }
        // test token validity
        $this->info('Testing token validity... ');
        $response = Http::get('https://graph.instagram.com/me',[
          'fields' => 'username',
          'access_token' => $userToken
        ]);

        if ($response->status() != 200) {
          $this->error('I tried to ping facebook with that token but the request failed, generate a new one and try again.');
          die();
        }

        $this->info('Success :) - retrieving user information');
        $correctUser = $this->confirm("Facebook says this token is for the username '{$response->json()['username']}', is that correct?");

        if (!$correctUser) {
          $this->info('Seems something went wrong with the token, try generating a new one in the facebook app and trying again.');
        }

        $friendlyName = $this->ask('Please choose a name to store this account as, this can be anything you like and just helps to identify the account in lists');


        // confirm save, alert with thank you

    }
}
