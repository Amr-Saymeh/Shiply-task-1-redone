# Documentation

## Prerequisites
1. Docker Desktop
2. Docker Compose
3. Composer
4. Git
5. Postman (for API testing)

---

## Installation & Setup

### 1. Clone the project
```bash 
git clone https://github.com/Amr-Saymeh/Shiply-task-1-redone.git
```
```bash 
cd Shiply-task-1
```
---

### 2. Copy environment file and configure it
```bash 
cp .env.example .env
```
---

### 3. Start Docker containers
```bash 
docker compose up -d
```
---

### 4. Install dependencies while inside the container
```bash 
docker compose exec laravel.test composer install --prefer-dist --no-progress --no-interaction
```
---

### 5. Generate application key
```bash 
docker compose exec laravel.test php artisan key:generate
```
---

### 6. Rebuild containers
```bash 
docker compose down -v
```
```bash 
docker compose build --no-cache
```
```bash 
docker compose up -d
```
---

### 7. Run migrations
Wait a few seconds after containers start before running this:
```bash 
docker compose exec laravel.test php artisan migrate
```
---

## API Base URL
```bash 
http://127.0.0.1:8080/api
```
---

## Testing with Postman (my test)

#### This is a test user to get you started with the testing:

### Register user
POST http://127.0.0.1:8080/api/register

```bash 
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "confirm_password": "password123"
}
```

---

### Login user
POST http://127.0.0.1:8080/api/login

```bash 
{
  "email": "john@example.com",
  "password": "password123"
}
```
Response:
```bash 
{
  "token": "THE_TOKEN"
}
```
---

## Authentication

Add header to ALL protected requests:
Authorization: Bearer THE_TOKEN
Accept: application/json

---

## API Endpoints

### Products

GET    /product

POST   /product

GET    /product/search?name=...


### Categories

GET    /category

POST   /category

#### You can test adding new categories and products, and then test the enforced validation and the new search functionality.

