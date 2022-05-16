.PHONY: php


SUPPORTED_COMMANDS := php composer cube43 test test-and-bdd coverage cs static cbf test-module coverage-module static-baseline run-coverage checkmodule
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

# Display general help about this command
help:
	@echo "Cube43 Makefile."
	@echo "The following commands are available:"
	@echo "Pour passer des arguments ex: make composer \" require toto/toto --dev\" "
	@echo "N'oublier pas les guillemets et l'epace juste après les guillemets"
	@echo ""
	@echo "Container management :"
	@echo ""
	@echo "    make dup                    : Monte les containers docker"
	@echo "    make kill                   : tue les containers docker"
	@echo "    make login                  : Rentre dans le container php"
	@echo "    make php                    : Lance une command dans le container php (ex: make php \" php -v\")"
	@echo ""
	@echo "Dépendence:"
	@echo ""
	@echo "    make install                : Récupére les dépendences du projet"
	@echo "    make composer               : Lance une command composer (ex: make composer require package)"
	@echo "    make composer-valid         : Tests si composer est valide"
	@echo ""
	@echo "Tests:"
	@echo ""
	@echo "    make test                   : Lance les tests unitaire/E2E"
	@echo "    make test-and-bdd           : Lance les tests unitaire/E2E et crée la bdd"
	@echo "    make coverage               : Lance les tests unitaire/E2E avec coverage générer dans le dossier coverage/"
	@echo "    make coverage-open          : Ouvre coverage/ dans firefox"
	@echo "    make create-test-database   : Crée la BDD de test"
	@echo "    make infection              : Run infection"
	@echo ""
	@echo "Analyse:"
	@echo ""
	@echo "    make static                 : Analyse static du code"
	@echo "    make is-valid               : Lance les job : test, static, cbf, bdd-is-valid, composer-valid"
	@echo "    make cs                     : phpcs"
	@echo "    make cbf                    : phpcbf"
	@echo ""
	@echo "Bdd:"
	@echo ""
	@echo "    make bdd-show-diff          : Affiche les différences entre les entité et la bdd"
	@echo "    make bdd-is-valid           : Vérifier que les entités sont dans état valide et synchronisé avec la bdd"
	@echo "    make bdd-migration-generate : Génére une migrations"
	@echo ""
	@echo "Autre:"
	@echo ""
	@echo "    make init                   : Lance les job : dup install create-test-database install-assets"
	@echo "    make cube43                 : Lance la console cube43 (argument possible)"
	@echo "    make install-assets         : Install les assets"
	@echo ""

# alias for help target
all: help

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
	$(BASE) composer update

composer: dup
	$(BASE) composer $(COMMAND_ARGS)

composer-valid: dup
	$(BASE) composer validate

# Tests

codegenerator: dup
	$(BASE) ./src/Base/Ressource/bin/cube43 cube43:code-generator

test:
	$(BASE) php run-phpunit.php $(COMMAND_ARGS)

run-coverage:
	$(BASE) php run-phpunit.php $(COMMAND_ARGS) --coverage

test-and-bdd: create-test-database
	$(BASE) vendor/bin/phpunit $(COMMAND_ARGS)

coverage-open:
	firefox coverage/index.html

create-test-database:
	$(BASE) php ./bin/cube43-create-test-database

infection: dup
	$(BASE) phpdbg -qrr -d memory_limit=-1 ./vendor/bin/infection -j=5 $(COMMAND_ARGS)

blackfire:
	$(BASE) blackfire run vendor/bin/phpunit $(COMMAND_ARGS)

# Analyse

static:
	$(BASE) php run-phpstan.php $(COMMAND_ARGS)

static-clear-cache:
	$(BASE) rm -rf /tmp/phpstan/

static-baseline: dup
	$(BASE) php run-phpstan.php $(COMMAND_ARGS) --baseline

is-valid: test cs static bdd-is-valid composer-valid

cs:
	@echo "Attention, si des erreurs sont détectées sur gitlab et pas en local, il faut supprimer le cache local (make cs-clear-cache)"
	$(BASE) php -d memory_limit=-1 ./vendor/bin/phpcs $(COMMAND_ARGS)

cs-clear-cache:
	rm .phpcs-cache

cbf: dup
	$(BASE) php -d memory_limit=-1 ./vendor/bin/phpcbf $(COMMAND_ARGS)

# Bdd

bdd-show-diff: dup
	$(BASE) ./src/Base/Ressource/bin/cube43 orm:schema-tool:update --dump-sql

bdd-is-valid: dup
	$(BASE) ./src/Base/Ressource/bin/cube43 orm:validate-schema

bdd-migration-generate: dup
	$(BASE) ./src/Base/Ressource/bin/cube43 migrations:generate

code-generator: dup
	$(BASE) ./src/Base/Ressource/bin/cube43 cube43:code-generator

# Autre

init: dup install create-test-database install-assets

cube43: dup
	$(BASE) ./src/Base/Ressource/bin/cube43 $(COMMAND_ARGS)

install-assets: dup
	$(BASE) ./src/Base/Ressource/bin/cube43 cube43:assets:install

checkmodule:
	$(BASE) php -d memory_limit=-1 ./vendor/bin/phpcbf src/$(COMMAND_ARGS)
	$(BASE) php run-phpunit.php $(COMMAND_ARGS) --coverage
	$(BASE) php run-phpstan.php $(COMMAND_ARGS)