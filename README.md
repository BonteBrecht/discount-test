# Discount test

This repository contains a demo API that calculates discounts for passed orders according to a set or rules defined in https://github.com/teamleadercrm/coding-test

## Local setup

This project requires PHP 8.0 or later.

To set up, first start the docker container responsible for the mysql server.

```shell
docker-compose up mysql
```

Next, load dependencies and set up the database

```shell
./clean_install.sh
```

Lastly, start the server

```shell
./run.sh
```

And head to the API on http://localhost:8888

## Testing

Once the demo is set up, you should be able to test it using e.g.

```shell
curl -XPOST 'http://localhost:8888/calculate-discount' -H "Content-Type: application/json" --data '
{
  "id": "1",
  "customer-id": "1",
  "items": [
    {
      "product-id": "B102",
      "quantity": "10",
      "unit-price": "4.99",
      "total": "49.90"
    }
  ],
  "total": "49.90"
}'
```
Which should result in
```json
{"discounts":[{"name":"buy_6_get_1_free_switch","discount":"4.99"}],"total":"4.99"}
```
