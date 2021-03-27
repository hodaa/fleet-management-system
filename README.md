## Fleet Management System
bus-booking system

## Installation

*  `cp .env.example to .env`
* `docker-compose build`
* `docker-compose up`

If you got the problem of file permission ,you should go enter the php image
`docker-compose exec php sh`
then type `sudo chown -R www-data:www-data storage/`

* `php artisan migrate`
* `php artisan passport:install`
* `php artisan db:seed`



## Tools
* PHP7.4
* Laravel
* Mysql 8


## Testing
```
docker-compose exec php vendor/phpunit/bin
```


## APIs

This is the collection
https://www.getpostman.com/collections/b7dd975e95031cf62429

1- Generate token using
http://localhost:8081/api/v1/login
* Body
    * `email:user@fleet.com`
    * `password:12345678`

2- Show available seats
http://localhost:8081/api/v1/trips?start={start_point}&end={end_point}
* header 
    * `access-token:{token-generated}` 
    * `Accept:application/json`

2- Book a seats
http://localhost:8081/api/v1/trip/book
* header
    * `access-token:{token-generated}`
    * `Accept:application/json`
* Body
  * `pickup_point:mansoura`
  * `destination_point:tanta`
  * `seat_id:XYZ9`
##Usage
You can import database  directly ,it exists in the root folder.

    

    

