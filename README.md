### Set up

Only Docker is needed to set up and run this project.

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

### Routes

Method: ``POST`` Uri: ``\api\payment`` Responses: ``201, 400, 409, 500``

### Running console commands

``docker compose run --rm cli <COMMAND-NAME>``

The application specific commands are namespaced

Payments report - ``docker compose run --rm cli sf-payments:payments-report --date=<DATE-IN-FORMAT-YYYY-MM-DD>``

Since the application is containerized, there are two ways to import file.
An ``imports`` directory has been created in app's root directory, from here the files can be processed using relative path.

``docker compose run --rm cli sf-payments:payments-import --file payments.csv``

Alternatively, any path can be bound to container's imports directory.

``docker compose run --rm --volume="$PWD/../../some-host-directory":/var/www/html/imports cli sf-payments:payments-import --file payments.csv``