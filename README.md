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

Laravel Client App: Running on PHP 8.3-FPM.
Laravel Api App: Running on PHP 8.3-FPM.
RabbitMQ: For handling job queues.
Redis: For caching and session management.
Eclipse Mosquitto: MQTT broker.
