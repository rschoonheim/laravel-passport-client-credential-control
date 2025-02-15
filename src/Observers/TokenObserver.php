<?php

namespace Rschoonheim\LaravelPassportClientCredentialControl\Observers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use League\OAuth2\Server\Exception\OAuthServerException;

class TokenObserver
{
    public function creating(Token $token): void
    {
        $client = Passport::clientModel()::find($token->client_id);

        Log::warning('Token created', [
            'client_id' => $client,
        ]);

        // Client must be `Client Credential Grant`.
        //
        if ($client->password_client === true || $client->personal_access_client === true) {
            return;
        }

        // Collect allowed scopes for client.
        //
        $allowedScopes = DB::table('password_client_allowed_scopes')
            ->where('client_id', $client->id)
            ->select('scopes')
            ->get()
            // Flatten scopes.
            ->map(fn ($item) => json_decode($item->scopes))
            ->flatten()
            ->toArray();

        if (empty($allowedScopes)) {
            return;
        }

        // Check if token has not allowed scopes.
        //
        $hasNotAllowedScopes = collect($token->scopes)
            ->diff($allowedScopes)
            ->isNotEmpty();

        if ($hasNotAllowedScopes) {
            $token->revoked = true;
            throw new OAuthServerException('Requested scope(s) not allowed for client', 401, 'not_allowed_scopes');
        }

    }
}
