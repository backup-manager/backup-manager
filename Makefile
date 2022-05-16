.PHONY: php


SUPPORTED_COMMANDS := php test cs static cbf
SUPPORTS_MAKE_ARGS := $(findstring $(firstword $(MAKECMDGOALS)), $(SUPPORTED_COMMANDS))
ifneq "$(SUPPORTS_MAKE_ARGS)" ""
  COMMAND_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  COMMAND_ARGS := $(subst :,\:,$(COMMAND_ARGS))
  $(eval $(COMMAND_ARGS):;@:)
endif

DOCKER_PHP := docker-compose exec php

ifeq (, $(shell which docker-compose))
	BASE :=
else
	BASE := $(DOCKER_PHP)
endif

# Container management

dup:
    ifeq ($(BASE), $(DOCKER_PHP))
		docker-compose up -d
    endif

kill:
	docker-compose rm -f -s

login:
	docker-compose exec php sh

php:
	$(BASE) $(COMMAND_ARGS)

# Dépendence

install: dup
	$(BASE) composer install

update: dup
	$(BASE) composer update

composer-valid: dup
	$(BASE) composer validate

# Tests

test:
	$(BASE) ./vendor/bin/phpunit $(COMMAND_ARGS)

infection: dup
	$(BASE) ./vendor/bin/infection

# Analyse

static:
	$(BASE) ./vendor/bin/psalm --show-info=true $(COMMAND_ARGS)

cs:
	$(BASE) php -d memory_limit=-1 ./vendor/bin/phpcs $(COMMAND_ARGS)

cbf: dup
	$(BASE) php -d memory_limit=-1 ./vendor/bin/phpcbf $(COMMAND_ARGS)

is-valid:
	$(BASE) ./vendor/bin/phpunit $(COMMAND_ARGS)
	$(BASE) ./vendor/bin/infection
	$(BASE) ./vendor/bin/psalm --show-info=true $(COMMAND_ARGS)
	$(BASE) php -d memory_limit=-1 ./vendor/bin/phpcs $(COMMAND_ARGS)