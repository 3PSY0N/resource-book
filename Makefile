.PHONY: yarn-dev
yarn-dev:
	yarn dev

.PHONY: symfony-server-start
symfony-server-start:
	symfony server:start

.PHONY: start
start:
	make -j2 yarn-dev symfony-server-start
