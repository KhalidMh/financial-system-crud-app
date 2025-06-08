# PHP CRUD Application

A simple yet robust CRUD (Create, Read, Update, Delete) application built with vanilla PHP 8.2 and MySQL 8.0. This project doesn't use any PHP frameworks, focusing on clean, straightforward PHP code.

## Features

- Client management
- Transaction processing
- User authentication
- Reporting functionality
- RESTful API endpoints

## Requirements

- PHP 8.2 or higher
- MySQL 8.0
- Composer
- Docker and Docker Compose (optional, for containerized setup)

## Installation and Setup

You can run this application in two ways: using Docker or setting it up manually.

### Option 1: Using Docker (Recommended)

This method requires Docker and Docker Compose to be installed on your system.

1. Clone this repository:
   ```bash
   git clone git@github.com:KhalidMh/financial-system-crud-app.git crud-app
   cd crud-app
   ```

2. Create a `.env` file in the project root:
   ```bash
   cp .env.example .env
   ```
   Then modify the `.env` file with your desired database credentials

3. Start the Docker containers:
   ```bash
   docker compose up -d
   ```

4. Install PHP dependencies:
   ```bash
   docker exec php composer install
   ```

5. Access the application:
   - Web app: [http://localhost:8080](http://localhost:8080)
   - API: [http://localhost:8080/api/movements.php](http://localhost:8080/api/movements.php)

### Option 2: Manual Setup

1. Install PHP dependencies:
   ```bash
   composer install
   ```

2. Create a MySQL database:
   ```sql
   CREATE DATABASE crud_app;
   CREATE USER 'user'@'localhost' IDENTIFIED BY 'pass123';
   GRANT ALL PRIVILEGES ON crud_app.* TO 'user'@'localhost';
   FLUSH PRIVILEGES;
   ```

3. Import the database schema:
   ```bash
   mysql -u user -p crud_app < database/schema.sql
   ```

5. Configure your web server (Apache/Nginx) to point to the `public` directory as the document root.

   For Apache, you might add this to your virtual host configuration:
   ```apache
   DocumentRoot /path/to/crud-app/public
   <Directory /path/to/crud-app/public>
       AllowOverride All
       Require all granted
   </Directory>
   ```

   For Nginx, you might use:
   ```nginx
   server {
       listen 80;
       server_name your-domain.com;
       root /path/to/crud-app/public;
       index index.php;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
           fastcgi_index index.php;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
           include fastcgi_params;
       }
   }
   ```

6. Create a `.env` file:
   ```bash
   cp .env.example .env
   ```
   Then update it with your database credentials.

7. Ensure the following PHP extensions are enabled:
   ```bash
   sudo apt-get install php8.2-mysql php8.2-mysqli
   # Or for other distributions, use their package manager
   ```

8. Access the application via your configured web server.

## Project Structure

```
├── config/           # Configuration files
├── database/         # Database schema
├── docker/           # Docker configuration files
├── public/           # Public files (entry point)
│   ├── api/          # API endpoints
│   ├── css/          # Stylesheets
│   ├── js/           # JavaScript files
│   └── index.php     # Main entry point
├── src/              # Application source code
│   ├── Controllers/  # Controller classes
│   ├── Middleware/   # Middleware classes
│   ├── Models/       # Model classes
│   └── Views/        # View templates
└── vendor/           # Composer dependencies
```

## Database Configuration

Database connection settings can be modified in the `.env` file or in `config/database.php`. The default settings in Docker mode are:

- Database host: `mysql`
- Database name: `crud_app`
- Username: `user`
- Password: `pass123`

## Debugging

This project includes Xdebug for debugging when using Docker. The Xdebug configuration can be found in `docker/php/xdebug.ini`.

## API Endpoints

The application provides RESTful API endpoints at `/api/movements.php` for working with transaction data.

## Development

### Running PHP Built-in Server (for development only)

If you're not using Docker, you can use PHP's built-in server for development:

```bash
cd public
php -S localhost:8000
```

Then access the application at [http://localhost:8000](http://localhost:8000)

### Stopping Docker Containers

```bash
docker compose down
```

To also remove volumes:
```bash
docker compose down -v
```

