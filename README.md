## Simple rate calculation App
### Phalcon/MySQL in Docker environment are used

Build Steps
1. Checkout and run ```docker-compose build```
2. Then run ```docker-compose up -d``` 
3. SSH to the db container and execute (see the pass in docker-compose.yml file) ```mysql -u root -p app < /opt/schema.sql```
4. The port for access is *8088*
5. For running the test from the tests dir run (in the app container) ```../../vendor/phpunit/phpunit/phpunit```
