init:
	composer install && sudo php artisan migrate:fresh --seed  && sudo php artisan passport:install && sudo chown -R www-data:www-data $(pwd) && sudo chmod 777 -R ./storage 