<?php

namespace Rschoonheim\LaravelPassportClientCredentialControl\Observers;

use Illuminate\Support\Facades\Log;
use Laravel\Passport\Token;

class TokenObserver
{
    public function creating(Token $token): void
    {
        Log::warning('Token created', [
            'client_id' => $token->client->id,
            'user_id' => null,
        ]);

        if ($token->client->isConfidential()) {
            return;
        }

        $token->forceFill([
            'client_id' => $token->client->id,
            'user_id' => null,
        ]);
    }
}
