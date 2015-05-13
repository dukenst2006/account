## Local Dev Environment

 * Setup [Laravel Homestead](https://github.com/laravel/homestead)
 * Clone this repo to `~/Personal` or wherever you would like it to live
 * Add `192.168.10.10   bbowl.local` to `/etc/hosts` 
 * Configure Homestead by typing `homestead edit`:
    * Add database `bbowl_management`
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
```
 * Run migrations with `php artisan migrate` to build the database
 * Open [http://bbowl.local](http://bbowl.local) in your browser
 
## Pull Requests

Please branch off of `master` and when your branch is ready to get merged back into master please create a pull request and assign it to BKuhl.