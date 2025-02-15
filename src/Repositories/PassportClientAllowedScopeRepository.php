<?php

namespace Rschoonheim\LaravelPassportClientCredentialControl\Repositories;

use Illuminate\Support\Facades\DB;

class PassportClientAllowedScopeRepository
{
    public function getScopesByClientId(int|string $clientId): ?array
    {
        $column = config('passport.client_uuids', false) ? 'client_uuid' : 'client_id';
        $scopeQuery = DB::table('password_client_allowed_scopes')->where($column, $clientId);

        if (! $scopeQuery->exists()) {
            return null;
        }

        return json_decode($scopeQuery->value('scopes'), true);
    }

    public function updateClientScopes(int|string $clientId, array $scopes): void
    {
        $column = config('passport.client_uuids', false) ? 'client_uuid' : 'client_id';
        DB::table('password_client_allowed_scopes')->where($column, $clientId)->update([
            'scopes' => json_encode($scopes),
        ]);
    }

    public function syncTokenScopes(int|string $clientId): void
    {
        $scopes = $this->getScopesByClientId($clientId);
        DB::table('oauth_access_tokens')->where('client_id', $clientId)->update([
            'scopes' => json_encode($scopes),
        ]);
    }
}
