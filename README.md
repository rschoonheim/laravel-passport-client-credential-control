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
