# AltwymWareHouseAgendaSysteem

WareHouseAgenda is a Laravel-based project designed to manage and organize warehouse operations efficiently.

## Installation

To get started with WareHouseAgenda, follow these steps:

1. **Clone the repository:**
    ```bash
    git clone https://github.com/JJRoel/AltwymWareHouseAgendaSysteem.git
    ```
2. **Navigate to the project directory:**
    ```bash
    cd AltwymWareHouseAgendaSysteem
    ```
3. **Install the required dependencies:**
    ```bash
    composer install
    ```
4. **Create a new `.env` file:**
    ```bash
    cp .env.example .env
    ```
    5. **Edit the `.env` file to configure your environment:**
    ```
    APP_NAME=Laravel
    APP_ENV=local
    APP_KEY=
    APP_DEBUG=true
    APP_URL=http://localhost

    LOG_CHANNEL=stack
    LOG_DEPRECATIONS_CHANNEL=null
    LOG_LEVEL=debug

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password

    BROADCAST_DRIVER=log
    CACHE_DRIVER=file
    FILESYSTEM_DRIVER=local
    QUEUE_CONNECTION=sync
    SESSION_DRIVER=file
    SESSION_LIFETIME=120

    MEMCACHED_HOST=127.0.0.1

    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379

    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS=null
    MAIL_FROM_NAME="${APP_NAME}"

    AWS_ACCESS_KEY_ID=
    AWS_SECRET_ACCESS_KEY=
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=

    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    PUSHER_APP_CLUSTER=mt1

    MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
    MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
    ```
6. **Generate the application key:**
    ```bash
    php artisan key:generate
    ```
7. **Run the database migrations:**
    ```bash
    php artisan migrate
    ```
8. *(Optional)* Seed the database with test data:
    ```bash
    php artisan db:seed
    ```
    Note: The seeders are optional. The user seeder can be useful as it creates a test user and configures the application to select the first user by default.

## Usage

To start the development server, run:
```bash
php artisan serve
