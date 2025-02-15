<?php

namespace Rschoonheim\LaravelPassportClientCredentialControl\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OauthClientCreateCommand extends Command
{
    public $signature = 'passport:cc:create';

    public $description = 'Creates a new controlled client.';

    public function handle(): int
    {
        $name = $this->ask('What is the name of the client?');
        $scopes = $this->ask('What are the allowed scopes? (comma separated)');

        $this->call('passport:client', [
            '--client' => true,
            '--name' => $name,
        ]);

        // Grab the client from the database
        //
        $client = \Laravel\Passport\Client::where('name', $name)->latest()->first();

        // Convert scopes into an array
        //
        $scopes = explode(',', $scopes);
        $scopes = array_map('trim', array_unique(array_filter($scopes)));

        DB::table('password_client_allowed_scopes')->insert([
            'client_id' => $client->id,
            'scopes' => json_encode($scopes),
        ]);

        $this->comment('All done');

        return self::SUCCESS;
    }
}
