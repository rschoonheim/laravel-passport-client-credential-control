# Laravel Passport Client Credentials Control

Laravel passport client credential grant is used authenticate machine-to-machine communication. Each client can retrieve
an access token by providing their client id and client secret. There are no capabilities to restrict the scopes that
the
client can request. This is a potential security risk as the client can request any scope that is available in the
system.

This package provides a way to have control over the scopes that a client can request.

## Installing the package

To install the package, use the following command:

```bash
```

## Using the package

### Creating a client

To create a controlled client (cc) use the following command:

```bash
$ php artisan passport::cc:create
```

It will ask you for the name of the client and what scopes the client can request.

```bash
 What is the name of the client?:
 > example
 
What are the allowed scopes? (comma separated):
 > scope1,scope2,scope3
```

The client id and client secret will be displayed after the client is created.

#### What is happening behind the scenes?

When a controlled client is created, a new client is created using Laravel Passport (using artisan command:
`passport:client --client`).
In the `password_client_allowed_scopes` table, are the allowed scopes for the client.

