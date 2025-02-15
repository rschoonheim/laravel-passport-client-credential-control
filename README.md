# Laravel Passport Client Credentials Control

Laravel passport client credential grant is used authenticate machine-to-machine communication. Each client can retrieve
an access token by providing their client id and client secret. There are no capabilities to restrict the scopes that the
client can request. This is a potential security risk as the client can request any scope that is available in the system.

This package provides a way to have control over the scopes that a client can request.

