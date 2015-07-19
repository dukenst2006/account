<?php
// Here you can initialize variables that will be available to your tests
\Codeception\Util\Autoload::register('Lib', '', realpath(__DIR__.'/..'.DIRECTORY_SEPARATOR.'lib'));

echo "Resetting/seeding database...";
shell_exec('php artisan migrate:refresh --seed');
echo "done";