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
 * Run migrations with `php artisan migrate` to build the database
 * Open [http://bbowl.local](http://bbowl.local) in your browser