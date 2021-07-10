# Discount test

This repository contains a demo API that calculates discounts for passed orders according to a set or rules defined in https://github.com/teamleadercrm/coding-test

## Local setup

This project requires PHP 8.0 or later.

To set up, first start the docker container responsible for the mysql server.

```shell
docker-compose up mysql
```

Lastly, start the server

```shell
./run.sh
```

And head to the API on http://localhost:8888
