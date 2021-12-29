## Datababse
migrate:
	bin/console doctrine:migrations:migrate

## Encore
watch:
	yarn encore dev --watch

build:
	yarn encore production

## Production
deploy: build
	rsync -av ./ 665kj_webapp@smnnt.fr:~/smnnt_app --include=public/build --exclude-from=.gitignore --exclude=".git/"

## On server
install:
	composer2 install --no-dev --optimize-autoloader

clear:
	APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear

prod: install clear migrate
