<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class AddUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Example of CRON';

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
        $user = User::create([
                'name' =>"dummy",
                'email' => date('YmdHis')."email@dummy.gmail.com",
                'phone' => '085742724990'
                
            ]);
        $user->save();
    }
}
