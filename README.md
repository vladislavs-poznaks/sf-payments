### Set up

Copy .env variables, optionally set different ports. 
Note that if ``APP_ENV`` is set to ``development``, it will seed example loan data.

``cp .env.example .env``

Install the dependencies

``docker compose run --rm composer install``

Run the migrations

``docker compose run --rm migrations migrate``

### Tests

``docker compose run --rm phpunit tests``

### Starting project

``docker compose up -d nginx``