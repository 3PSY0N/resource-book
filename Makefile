.DEFAULT_GOAL := help
COMPOSER = $(SYMFONY_BIN) composer
SF_CONSOLE = $(SYMFONY) console
SYMFONY = symfony
YARN = yarn

help: ## Get command list
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


## ----- Development -----
run-symfony: ## Start symfony HTTP
	$(SYMFONY) server:start --port=80 --no-tls

run-symfony-tls: ## Start symfony HTTPS
	$(SYMFONY) server:start --port=443

yarn-dev: ## start vite in watch mode
	$(YARN) dev

yarn-build: ## start vite and build assets
	$(YARN) dev

dev: ## Start Dev servers
	@make -j2 run-symfony yarn-dev


## ----- Symfony -----
db: ## Build the DB, control the schema validity and check the migration status
	$(SF_CONSOLE) doctrine:cache:clear-metadata
	$(SF_CONSOLE) doctrine:database:create --if-not-exists
	$(SF_CONSOLE) doctrine:schema:drop --force
	$(SF_CONSOLE) doctrine:schema:create
	$(SF_CONSOLE) doctrine:schema:validate

fixtures: ## Load default fixtures
	$(SF_CONSOLE) doctrine:fixtures:load --no-interaction

fixtures-a: ## Load default fixtures + append data
	$(SF_CONSOLE) doctrine:fixtures:load --append --no-interaction

migdiff: ## Generate a migration by comparing your current database to your mapping information.
	$(SF_CONSOLE) doctrine:migrations:diff

migexec: ## Execute a single migration version up or down manually.
	$(SF_CONSOLE) doctrine:migrations:execute

dsu: ## Update database schema to match current mapping metadata.
	$(SF_CONSOLE) doctrine:schema:update --force


## ----- Cleaning -----
cc: ## Clear Symfony cache
	$(SF_CONSOLE) c:c

clogs: ## Delete cache and logs directory
	@rm -rf var/cache/
	@echo "var/ purged"

cassets: ## Delete node_modules directory
	@rm -rf node_modules/
	@echo "Assets purged"

cvendor: ## Delete vendor directory
	@rm -rf vendor/
	@echo "Vendors purged"

cbuild: ## Delete public/build/
	@rm -rf public/build/
	@echo "Public build purged"

clearall: clogs cassets cvendor cbuild ## Delete var/cache/ node_modules/ vendor/ public/build/ directories
	@echo "Assets purged"