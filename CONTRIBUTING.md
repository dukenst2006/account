## Deployments

Deployments via docker containers are automated.  Travis-CI builds containers with the `-latest` suffix which are automatically deployed.

## Local Dev Environment

 * This project uses [Docker](https://www.docker.com), so run `docker-compose up`
 * Run `cp .env.example .env`
 * Run `echo '127.0.0.1   bbowl.local' >> /etc/hosts`
 * Run migrations with `php artisan migrate --seed` to build the database
 * Open [http://bbowl.local](http://bbowl.local) in your browser

> To enable 3rd party integration, you'll need to setup your own client ids

## Pull Requests

Please branch off of `master` and when your branch is ready to get merged back into master please create a pull request and assign it to BKuhl.
 
### Running PHPUnit tests

Run `composer test`, see `composer.json` for details on what this does.
 
### Testing OAuth2 Integration

 * obtain the `PROVIDER_CLIENT_ID`/`PROVIDER_CLIENT_SECRET` and add it to the config the `.env` file under the provider you wish to test with
Should be something like:
 * Add `192.168.10.10   manage.biblebowl.org` to `/etc/hosts` so that the third party provider redirects you back to your local application rather than production
