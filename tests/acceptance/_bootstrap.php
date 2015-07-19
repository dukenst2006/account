<?php
// Here you can initialize variables that will be available to your tests

echo "Resetting/seeding database...";
shell_exec('php artisan migrate:refresh --seed');
echo "done";