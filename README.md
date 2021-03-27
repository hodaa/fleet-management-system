## Fleet Management System
bus-booking system

## Installation
* `docker-compose build`
* `docker-compose up`

If you got the problem of file permission ,you should go enter the php image
`docker-compose exec php sh`
then type `sudo chown -R www-data:www-data storage/`

* `php artisan migrate`


## Tools
* PHP7.4
* Laravel
* Mysql 8


## Testing
`vendor/bin/phpunit`


## APIs
1- Generate token using
http://localhost:8081/oauth/token
`grant_type:client_credentials`
`client_id:1`
`client_secret:lTxmtOLeXLA65VmZ1cbU2C23aUwsrN5QSabkOvnp`

2- Show available seats
http://localhost:8081/api/v1/trips?start={start_point}&end={end_point}
* header 
    * access-token:{token-generated}  
    * Accept:application/json
    

    

