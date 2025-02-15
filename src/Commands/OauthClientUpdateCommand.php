<?php

namespace Rschoonheim\LaravelPassportClientCredentialControl\Commands;

use Illuminate\Console\Command;
use Laravel\Passport\Passport;
use Rschoonheim\LaravelPassportClientCredentialControl\Repositories\PassportClientAllowedScopeRepository;

class OauthClientUpdateCommand extends Command
{
    public $signature = 'passport:cc:update';

    public $description = 'Update an existing controlled client.';

    private PassportClientAllowedScopeRepository $passportClientAllowedScopeRepository;

    public function __construct(PassportClientAllowedScopeRepository $passportClientAllowedScopeRepository)
    {
        parent::__construct();
        $this->passportClientAllowedScopeRepository = $passportClientAllowedScopeRepository;
    }

    public function handle(): int
    {
        $id = $this->ask('What is the id of the client?');
        $client = Passport::clientModel()::find($id);
        if (!$client) {
            $this->error('Client not found.');
            return self::FAILURE;
        }

        $scopes = array_map('trim', array_unique(array_filter(explode(',', $this->ask('What are the allowed scopes? (comma separated)')))));

        // Review changes
        $this->table(['Current scopes'], array_map(fn($scope) => [$scope], $this->passportClientAllowedScopeRepository->getScopesByClientId($client->id)));
        $this->table(['New scopes'], array_map(fn($scope) => [$scope], $scopes));

        if (!$this->confirm('Do you want to update the allowed scopes of the client?')) {
            $this->comment("No changes made.");
            return self::SUCCESS;
        }

        // Update scopes
        $this->passportClientAllowedScopeRepository->updateClientScopes($client->id, $scopes);
        $this->passportClientAllowedScopeRepository->syncTokenScopes($client->id);

        return self::SUCCESS;
    }
}
