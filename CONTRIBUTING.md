## Local Dev Environment

 * Setup [Laravel Homestead](https://github.com/laravel/homestead)
 * Clone this repo to `~/Personal` or wherever you would like it to live.
 * Run `composer install`, `npm install` and then `gulp` from the project directory (can also run on Homestead)
 * Add `192.168.10.10   bbowl.local` to `/etc/hosts` 
 * Configure Homestead by typing `homestead edit`:
    * Add database `biblebowl`
    * Add a synced folder mapping:

```
folders:
    - map: ~/Personal
      to: /var/www/Personal
```

    * Add a site mapping:
    
```
sites:
    - map: bbowl.local
      to: /var/www/Personal/biblebowl/public
```

 * Run `homestead up`
 * In the project root, create a `.env` file that will house your local configuration.  Use the below code block to populate this file:
```
APP_ENV=local
APP_DEBUG=true
APP_KEY=z7FIVROjI3UE7zbsGFKktgxORMSMvjux

DB_HOST=192.168.10.10
DB_DATABASE=biblebowl
DB_USERNAME=homestead
DB_PASSWORD=secret

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

MAIL_DRIVER=smtp
MAIL_HOST=mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null

MAILCHIMP_API_KEY=
MAILCHIMP_LIST_ID=

FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT=

TWITTER_CLIENT_ID
TWITTER_CLIENT_SECRET
TWITTER_REDIRECT=

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT=

GOOGLE_API_KEY=
```
 * Run migrations with `php artisan migrate --seed` to build the database
 * Open [http://bbowl.local](http://bbowl.local) in your browser

> To enable 3rd party integration, you'll need to setup your own client ids

## Pull Requests

Please branch off of `master` and when your branch is ready to get merged back into master please create a pull request and assign it to BKuhl.

## Tests
For this application, tests are broken down by...

 * **Acceptance tests** test the UI/application flow
 * **Functional tests** test the internal workings of the application
 
### Running Acceptance tests
 * [Download Selenium](http://www.seleniumhq.org/download/)
 * Run it with `java -jar /path/to/file`
 * Run the tests with `./vendor/bin/codecept run acceptance`
 * (Optional) You can also run acceptance tests with `gulp codecept`.
 
### Running PHPUnit tests
Some PHPUnit tests utilize Laravel's test helpers and are actually acceptance tests.  To get them running, you'll need to increase [xdebug's max nesting](https://laracasts.com/discuss/channels/general-discussion/l5-maximum-function-nesting-level-of-100-reached-aborting).
 
### Testing OAuth2 Integration

 * obtain the `PROVIDER_CLIENT_ID`/`PROVIDER_CLIENT_SECRET` and add it to the config the `.env` file under the provider you wish to test with
Should be something like:
 * Add `192.168.10.10   manage.biblebowl.org` to `/etc/hosts` so that the third party provider redirects you back to your local application rather than production

### Gulp Commands
 * `gulp` - _runs all tasks_
 * `gulp watch` - _watch for code changes and recompile assets._
 * `gulp codecept` - _Run the acceptance tests._
 * `gulp codecept-build`  - _Generate helper classes for tests_

 > Selenium must be running when `gulp codecept` is executed.

