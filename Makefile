migrate:
	bin/console doctrine:migrations:migrate

deploy:
	rsync -av ./ 665kj_webapp@smnnt.fr:~/smnnt_app --exclude-from=.gitignore --exclude=".git/"

install:
	composer2 install --no-dev --optimize-autoloader

clear:
	APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear

prod: install clear migrate