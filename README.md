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
- XAMPP (optional, for local development)

## Installation and Setup

You can run this application in three ways: using Docker, XAMPP, or setting it up manually.

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

### Option 2: Using XAMPP (Great for Local Development)

This is perfect for developers who prefer XAMPP for local development.

**For detailed XAMPP setup instructions, see [XAMPP_SETUP.md](XAMPP_SETUP.md)**

Quick setup:
1. Install XAMPP with PHP 8.2
2. Place project in `htdocs/php-crud-app/`
3. Run `composer install`
4. Copy `.env.xampp` to `.env`
5. Import `database/schema.sql` via phpMyAdmin
6. Access: [http://localhost/php-crud-app/public](http://localhost/php-crud-app/public)

Default login:
- Username: `admin`
- Password: `admin123`

### Option 3: Manual Setup

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

4. Configure your web server (Apache/Nginx) to point to the `public` directory as the document root.

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

5. Create a `.env` file:
   ```bash
   cp .env.example .env
   ```
   Then update it with your database credentials.

6. Ensure the following PHP extensions are enabled:
   ```bash
   sudo apt-get install php8.2-mysql php8.2-mysqli
   # Or for other distributions, use their package manager
   ```

7. Access the application via your configured web server.

## Project Structure

```
├── config/           # Configuration files
├── database/         # Database schema
├── docker/           # Docker configuration files
├── public/           # Public files (entry point)
│   ├── .htaccess     # URL rewriting (for Apache/XAMPP)
│   ├── api/          # API endpoints
│   ├── css/          # Stylesheets
│   ├── js/           # JavaScript files
│   └── index.php     # Main entry point
├── src/              # Application source code
│   ├── Controllers/  # Controller classes
│   ├── Middleware/   # Middleware classes
│   ├── Models/       # Model classes
│   └── Views/        # View templates
├── vendor/           # Composer dependencies
├── .env.xampp        # XAMPP-specific environment file
└── XAMPP_SETUP.md    # Detailed XAMPP setup guide
```

## Database Configuration

Database connection settings can be modified in the `.env` file or in `config/database.php`. 

**Default settings by environment:**

**Docker mode:**
- Database host: `mysql`
- Database name: `crud_app`
- Username: `user`
- Password: `pass123`

**XAMPP mode:**
- Database host: `localhost`
- Database name: `crud_app`
- Username: `root`
- Password: (empty)

## Debugging

This project includes Xdebug for debugging when using Docker. The Xdebug configuration can be found in `docker/php/xdebug.ini`.

For XAMPP debugging, you can enable Xdebug through XAMPP's PHP configuration.

## API Endpoints

The application provides RESTful API endpoints at `/api/movements.php` for working with transaction data.

## Development

### Running PHP Built-in Server (for development only)

If you're not using Docker or XAMPP, you can use PHP's built-in server for development:

```bash
cd public
php -S localhost:8000
```

Then access the application at [http://localhost:8000](http://localhost:8000)

### XAMPP Development

For XAMPP users, the application runs directly through Apache. See [XAMPP_SETUP.md](XAMPP_SETUP.md) for detailed instructions.

### Stopping Docker Containers

```bash
docker compose down
```

To also remove volumes:
```bash
docker compose down -v
```

## Login Credentials

Default admin credentials:
- Username: `admin`
- Password: `admin123`

**Important:** Change these credentials in production environments!

