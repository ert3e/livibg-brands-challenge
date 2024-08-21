# Laravel Microservices with RabbitMQ and MQTT

This project demonstrates a microservice architecture using Laravel, RabbitMQ, and MQTT for inter-service communication.

## Prerequisites

Before you start, make sure you have the following installed:

- Docker and Docker Compose
- PHP 8.3
- Composer

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/your-repo-name.git
cd your-repo-name
```
### 2. Setup Environment

Copy the .env.example file to .env:
```bash
cp .env.example .env
```

### 3. Build and Run Docker Containers

Here's a sample README.md file that explains how to install and run your Laravel application with RabbitMQ, MQTT, and Docker:

markdown
Copy code
# Laravel Microservices with RabbitMQ and MQTT

This project demonstrates a microservice architecture using Laravel, RabbitMQ, and MQTT for inter-service communication.

## Prerequisites

Before you start, make sure you have the following installed:

- Docker and Docker Compose
- PHP 8.3
- Composer

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/your-repo-name.git
cd your-repo-name
```
### 2. Setup Environment
Copy the .env.example file to .env:

```bash
cp .env.example .env
```
Update your .env file with the correct database, RabbitMQ, and MQTT configurations.

###  3. Build and Run Docker Containers
Run the following command to build and start the Docker containers:
```bash
sudo docker-compose up -d --build
```
This will start the following services:

- Laravel Client App: Running on PHP 8.3-FPM.
- Laravel Api App: Running on PHP 8.3-FPM.
- RabbitMQ: For handling job queues.
- Redis: For caching and session management.
- Eclipse Mosquitto: MQTT broker.
###  4. Install PHP Dependencies in API && CLIENT microservices and generate keys
Access the Laravel container and install the PHP dependencies:
```bash
sudo docker-compose exec api bash
composer install
php artisan key:generate
```
###  5. Run Queues in API microservice
```bash
php artisan queue:work
```
This will start listening for jobs on the RabbitMQ queue.

### 6. MQTT Configuration
- Your mqtt-broker service is already configured in the docker-compose.yml file.
- The configuration does not require authentication (username and password).

### 7 Run MQTT Subscriber in API microservice
```bash
php artisan mqtt:listen
```

### Usage
### Searching for a TV Show
### Send a request to the search endpoint to search for a TV show:
```bash
-GET /search?query=show-name
```

This will:

- Check if the result is cached in Redis.
- If not, send the search query to RabbitMQ via MQTT.
- Receive the result via MQTT.
- Return the response to the client.
### Troubleshooting
- Permission Issues: Ensure the Laravel directories have the correct permissions:
```bash
  sudo chown -R www-data:www-data storage bootstrap/cache
  sudo chmod -R 775 storage bootstrap/cache
```