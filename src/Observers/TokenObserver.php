<?php

namespace Rschoonheim\LaravelPassportClientCredentialControl\Observers;

use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use League\OAuth2\Server\Exception\OAuthServerException;

class TokenObserver
{
    public function creating(Token $token): void
    {
        $client = Passport::clientModel()::find($token->client_id);

        // Client must be `Client Credential Grant`.
        if ($client->password_client || $client->personal_access_client) {
            return;
        }

        // Collect allowed scopes for the token.
        $allowedScopes = $this->getAllowedScopes($token);

        // Return early if the client is uncontrolled.
        if (is_null($allowedScopes)) {
            return;
        }

        // Throw exception if no scopes are allowed for the client.
        if (empty($allowedScopes) && ! empty($token->scopes)) {
            throw new OAuthServerException('Requested scope(s) not allowed for client', 401, 'not_allowed_scopes');
        }

        // Delete token and throw exception if it has not allowed scopes.
        if (collect($token->scopes)->diff($allowedScopes)->isNotEmpty()) {
            $token->delete();
            throw new OAuthServerException('Requested scope(s) not allowed for client', 401, 'not_allowed_scopes');
        }
    }

    /**
     * Returns array containing allowed scopes for the client related to the token.
     *
     * @return array|null - array containing allowed scopes or null when client_id is not found in password_client_allowed_scopes table.
     */
    private function getAllowedScopes(Token $token): ?array
    {
        $uuids = config('passport.client_uuids', false);

        $scopeQuery = DB::table('password_client_allowed_scopes')
            ->where($uuids ? 'client_uuid' : 'client_id', $token->client_id);

        if (! $scopeQuery->exists()) {
            return null;
        }

        return $scopeQuery->pluck('scopes')
            ->map(fn ($scopes) => json_decode($scopes))
            ->flatten()
            ->toArray();
    }
}
