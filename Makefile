.PHONY: lint test

all: lint test
deps: deps.dev
lint: lint.php lint.phpcs lint.psalm
test: test.coverage

deps.dev:
	@composer global require hirak/prestissimo --no-interaction
	@composer install --ignore-platform-reqs

deps.prod:
	@composer install --prefer-dist --ignore-platform-reqs --no-interaction

test.coverage:
	@phpdbg -qrr vendor/bin/kahlan --coverage=4 --clover=coverage.xml --reporter=verbose

test.quick:
	@vendor/bin/kahlan --reporter=verbose

lint.php:
	@php -l src/
	@php -l spec/

lint.phpcs:
	@vendor/bin/phpcs --standard=phpcs.xml src spec

lint.phpcbf:
	-@vendor/bin/phpcbf --standard=phpcs.xml src spec

lint.psalm:
	@vendor/bin/psalm
