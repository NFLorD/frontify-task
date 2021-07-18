![main](https://github.com/NFLorD/frontify-task/actions/workflows/main.yml/badge.svg)

### Installing the project:
git clone git@github.com:NFLorD/frontify-task.git \
cd frontify-task

docker-compose build \
docker-compose up -d

docker exec -t frontify-task_php_1 sh -c "composer install"

### Preparing the dev environment:
docker exec -t frontify-task_php_1 sh -c "php /var/www/html/bin/console doctrine:database:create --if-not-exists \\ \
&& php /var/www/html/bin/console doctrine:migrations:migrate --no-interaction"

### Preparing the test environment:
docker exec -t frontify-task_php_1 sh -c "php /var/www/html/bin/console --env=test doctrine:database:create --if-not-exists \\ \
&& php /var/www/html/bin/console --env=test doctrine:migrations:migrate --no-interaction"

### Running the tests:
docker exec -t frontify-task_php_1 sh -c "php /var/www/html/vendor/bin/behat"

### Frontend (dev):
https://localhost:8080

### Frontend (prod):
https://localhost