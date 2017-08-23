MAKEFLAGS += --no-print-directory

test:
	@./vendor/bin/phpunit
stan:
	@./vendor/bin/phpstan analyse -l 7 src
cs:
	@./vendor/bin/phpcs --standard=PSR2 src

check:
	-make cs
	-make stan
	-make test

.PHONY: test stan cs check
