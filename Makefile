install:
	composer install
	php bin/console doctrine:database:create --if-not-exists
	php bin/console doctrine:migrations:migrate
	php bin/console doctrine:fixtures:load