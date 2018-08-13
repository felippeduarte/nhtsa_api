# NHTSA API

## Installation/Configuration

### 1. Clone this repository

`git clone https://github.com/felippeduarte/nhtsa_api.git`

### 2. Setup Environment

You can configure this project with Docker or Vagrant.

#### 2a. The Docker Way

- Install [Docker](https://docs.docker.com/install/)
- Install [Docker Compose](https://docs.docker.com/compose/install/)
- Go to root folder and run `sudo docker-compose --build -d`
- (grab some coffee, it will take some time)
- When the process finishes, the webserver will be running at `http://localhost:8080`

#### 2b. The Vagrant Way

- Install [VirtualBox](https://www.virtualbox.org/)
- Install [Vagrant](https://www.vagrantup.com/intro/getting-started/index.html)
- Go to root folder and run `vagrant up --provision`
- (grab some coffee, it will take some time)
- When the process finishes, the webserver will be running at `http://localhost:8080`

---

## Usage

By default, this API runs on `http://localhost:8080`
Below are the available functions

### GET Requests

Do a GET Request at [http://localhost:8080/vehicles/MODELYEAR/MANUFACTURER/MODEL](http://localhost:8080/vehicles/MODELYEAR/MANUFACTURER/MODEL)

Ex.: [http://localhost:8080/vehicles/2015/Audi/A3](http://localhost:8080/vehicles/2015/Audi/A3)

It should return

~~~~
{
    Count: <NUMBER OF RESULTS>,
    Results: [
        {
            Description: "<VEHICLE DESCRIPTION>",
            VehicleId: <VEHICLE ID>
        },...
    ]
}
~~~~

Do a GET Request at
[http://localhost:8080/vehicles/MODELYEAR/MANUFACTURER/MODEL?withRating=true](http://localhost:8080/vehicles/MODELYEAR/MANUFACTURER/MODEL?withRating=true)

Ex.: [http://localhost:8080/vehicles/2015/Audi/A3?withRating=true](http://localhost:8080/vehicles/2015/Audi/A3?withRating=true)

It should return

~~~~
{
    Count: <NUMBER OF RESULTS>,
    Results: [
        {
            CrashRating: "<CRASH RATING>"
            Description: "<VEHICLE DESCRIPTION>",
            VehicleId: <VEHICLE ID>
        },...
    ]
}
~~~~

### POST Request

Do a POST Request at [http://localhost:8080/vehicles](http://localhost:8080/vehicles)

With this body (application/json):

~~~~
{
    "modelYear": <MODEL YEAR>,
    "manufacturer": <MANUFACTURER>,
    "model": <MODEL>"
}
~~~~


It should return

~~~~
{
    Count: <NUMBER OF RESULTS>,
    Results: [
        {
            Description: "<VEHICLE DESCRIPTION>",
            VehicleId: <VEHICLE ID>
        },...
    ]
}
~~~~

### Documentation

You can also access the built-in documentation, made with [Swagger](https://swagger.io/), at [http://localhost:8080/api/documentation](http://localhost:8080/api/documentation)

#### Running Tests

With docker: `sudo docker-compose run php /var/www/html/vendor/bin/phpunit /var/www/html/tests/`
With Vagrant: Go to root `/vagrant/src` then run `vendor/bin/phpunit`