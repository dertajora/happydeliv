<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Token;

class GenerateTelkomToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:telkom_access_token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate access token from Telkom API every 30 minutes';

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

        // retrieve token account for authentification in DB
        $telkom_auth =  DB::table('token_configuration')->where('id', 1)->value('token');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.mainapi.net/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Authorization: Basic ".$telkom_auth;
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $data = json_decode($result);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);

        // update access token
        $access_token = Token::find(2);
        $access_token->token = $data->access_token;
        $access_token->save();
    }
}
