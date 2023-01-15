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

Please note, that by default application is served on port ``8888``

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

### Assumptions and clarifications

For imports the reference differed from API request example's UUID format. In order to not implement additional column, import data was adjusted. If needed separate refereneces, further development is required.
For imports console output it is not clear as to on what particular conditions the custom errors should be returned. On any record error or on all, should the valid data be not imported if there are errors.
For imports it is not cleared if payment should be also processed.

API part implemented

Communication NOT implemented

Console part implemented partly (except for error handling, needs clarification)

Logging NOT implemented

Testing partly implemented (for value objects)

As no framework was used, much time was spent on developing infrastructure instead of business logic.

### Further development

#### Business logic
1. Communication

#### Infrastructure
1. Implementing DI container
2. Allowing for custom requests objects to delegate validation and authorization
3. Implementing exception handler for logging purposes, implementing request middleware for logging purposes