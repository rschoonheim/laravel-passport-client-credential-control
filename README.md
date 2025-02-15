# Laravel Passport Client Credentials Control

Laravel Passport’s Client Credentials Grant is designed for machine-to-machine authentication, allowing clients to
obtain an access token using their client ID and secret. However, by default, there are no restrictions on the scopes a
client can request, posing a potential security risk.

This package provides a solution by enabling precise control over the scopes that each client can request.

## Installation

To install the package, run:

```bash
composer require rschoonheim/laravel-passport-client-credential-control
```

Next, publish the configuration and migration files:

```bash
php artisan vendor:publish --provider="Rschoonheim\LaravelPassportClientCredentialControl\LaravelPassportClientCredentialControlServiceProvider"
```

Then, apply the migration:

```bash
php artisan migrate
```

## Usage

### Creating a Controller Client

To create a client with restricted scopes, use the following command:

```bash
php artisan passport:cc:create
```

You will be prompted to provide a client name and specify the allowed scopes:

```bash
What is the name of the client?:
> example

What are the allowed scopes? (comma-separated):
> scope1,scope2,scope3
```

Once created, the client ID and secret will be displayed.

#### How It Works

When a controlled client is created, a new client is registered in Laravel Passport using the `passport:client --client`
command. The allowed scopes for the client are then stored in the `password_client_allowed_scopes` table, ensuring that
each client can only request explicitly permitted scopes.

### Updating scopes for a Controller Client

To update the scopes for a client, use the following command:

```bash
php artisan passport:cc:update
```

You will be prompted to provide the client ID and specify the new allowed scopes:

```bash
What is the client ID?:
> 1
```

Next, specify the new allowed scopes:

```bash
What are the allowed scopes? (comma-separated):
> new-scope1,new-scope2
```

Then you will see a confirmation message:

```bash
+----------------------+
| Current scopes       |
+----------------------+
| scope-1              |
| scope-2              |
| scope-3              |
+----------------------+
+------------+
| New scopes |
+------------+
| new-scope1 |
| new-scope2 |
+------------+

 Do you want to update the allowed scopes of the client? (yes/no) [no]:
 > yes
```
This will result in the allowed scopes for the client being updated and scopes on issued tokens being synced.
