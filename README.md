# Project Name

    VMS


## Requirements

Before installing, ensure you have the following tools:

- PHP >= 8.0
- Composer
- MySQL

## Installation

1. **Clone the repository:**

    ```sh
    git clone https://github.com/drilonmaloku/bug-venue-system.git
    cd bug-venue-system
    ```

2. **Install PHP dependencies:**

    ```sh
    composer install
    ```


## Configuration

1. **Copy the example environment file and edit it:**

    ```sh
    cp .env.example .env
    ```

`
## Database Setup

1. **Create the database:**

    Create a database in your MySQL 

2. **Run the migrations:**

    ```sh
    php artisan migrate
    ```

3. **(Optional) Seed the database:**

    ```sh
    php artisan db:seed  --class=ProductionSeeder
    ```

## Running the Application



1. **Start the local development server:**

    ```sh
    php artisan serve
    ```




