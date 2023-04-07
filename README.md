
# Blog Website

 Job Board Application Built w/ Laravel.

## Installation

Clone the project using SSH or HTTPS.

```bash
git@github.com:yuvraj-timalsina/laravel-job-board.git
```
    
## Run Locally

Go to the Project Directory

```bash
cd laravel-job-board
```

Create .env in root directory

```bash
cp .env.example .env
```

Configure Database Credentials in .env

```bash
DB_DATABASE=<db_name>
DB_USERNAME=<username>
DB_PASSWORD=<password>
```

Install Dependencies

```bash
composer install
```

Generate Application Key

```bash
php artisan key:generate
```

Run the Server

```bash
php artisan serve
  
http://127.0.0.1:8000
```

## Author

- [@yuvraj-timalsina](https://www.github.com/yuvraj-timalsina)
