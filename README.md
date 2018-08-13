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

###

#### Running Tests

With docker: `sudo docker-compose run php /var/www/html/vendor/bin/phpunit /var/www/html/tests/`
With Vagrant: Go to root `/vagrant/src` then run `vendor/bin/phpunit`