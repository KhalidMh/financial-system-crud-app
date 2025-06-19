# XAMPP Setup Guide for PHP CRUD Application

This guide will help you set up the PHP CRUD application on XAMPP with PHP 8.2.

## Prerequisites

- XAMPP with PHP 8.2 or higher
- Composer (can be installed separately or use the one that comes with some XAMPP distributions)

## Installation Steps

### 1. Download and Setup XAMPP
1. Download XAMPP with PHP 8.2 from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Install XAMPP to your preferred location (e.g., `C:\xampp` on Windows)
3. Start Apache and MySQL services from the XAMPP Control Panel

### 2. Setup the Application
1. Clone or download this project to your XAMPP's `htdocs` directory:
   ```
   C:\xampp\htdocs\php-crud-app\
   ```

2. Install Composer dependencies:
   ```bash
   cd C:\xampp\htdocs\php-crud-app
   composer install
   ```
   
   If you don't have Composer installed:
   - Download from [https://getcomposer.org/download/](https://getcomposer.org/download/)
   - Or use XAMPP's PHP to run Composer:
     ```bash
     C:\xampp\php\php.exe composer.phar install
     ```

### 3. Database Setup
1. Open phpMyAdmin in your browser: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Create a new database named `crud_app`
3. Import the database schema:
   - Go to the `crud_app` database
   - Click on "Import" tab
   - Select the file: `database/schema.sql`
   - Click "Go" to import

### 4. Environment Configuration
1. Copy the XAMPP environment file:
   ```bash
   copy .env.xampp .env
   ```
   
   Or manually create a `.env` file with these contents:
   ```
   DB_HOST=localhost
   DB_NAME=crud_app
   DB_USER=root
   DB_PASS=
   DB_PORT=3306
   ```

### 5. Configure Apache (if needed)
The application should work with XAMPP's default Apache configuration. However, if you encounter issues:

1. Ensure `mod_rewrite` is enabled in XAMPP:
   - Open `C:\xampp\apache\conf\httpd.conf`
   - Uncomment the line: `LoadModule rewrite_module modules/mod_rewrite.so`
   - Find the section for your document root and ensure `AllowOverride All` is set

2. Restart Apache from XAMPP Control Panel

### 6. Access the Application
1. Open your web browser
2. Navigate to: [http://localhost/php-crud-app/public](http://localhost/php-crud-app/public)
3. You should see the login page
4. Use these default credentials:
   - Username: `admin`
   - Password: `admin123`

## Troubleshooting

### Common Issues and Solutions

**1. "404 Not Found" for routes**
- Ensure `mod_rewrite` is enabled in Apache
- Check that `.htaccess` file exists in the `public` directory
- Verify Apache configuration allows `.htaccess` overrides

**2. Database connection errors**
- Verify MySQL is running in XAMPP Control Panel
- Check that the database `crud_app` exists in phpMyAdmin
- Ensure `.env` file has correct database credentials

**3. Composer dependencies missing**
- Run `composer install` in the project root directory
- If Composer is not installed globally, use XAMPP's PHP:
  ```bash
  C:\xampp\php\php.exe composer.phar install
  ```

**4. PHP extensions missing**
XAMPP should include all necessary extensions, but verify these are enabled:
- `pdo`
- `pdo_mysql`
- `mysqli`

**5. Permission issues (Linux/Mac)**
If running XAMPP on Linux or Mac:
```bash
chmod -R 755 /opt/lampp/htdocs/php-crud-app/
chown -R daemon:daemon /opt/lampp/htdocs/php-crud-app/
```

### Performance Tips for XAMPP

1. **Increase PHP memory limit** (if needed):
   - Edit `C:\xampp\php\php.ini`
   - Set: `memory_limit = 256M`

2. **Enable OPcache** for better performance:
   - In `php.ini`, uncomment: `zend_extension=opcache`
   - Set: `opcache.enable=1`

3. **Restart Apache** after making changes to `php.ini`

## Development Tips

### Using XAMPP's Built-in Tools
- **phpMyAdmin**: [http://localhost/phpmyadmin](http://localhost/phpmyadmin) - Database management
- **PHP Info**: Create a file with `<?php phpinfo(); ?>` to check PHP configuration

### API Testing
- The application provides API endpoints at: [http://localhost/php-crud-app/public/api/movements.php](http://localhost/php-crud-app/public/api/movements.php)
- Use tools like Postman or curl to test API endpoints

### File Structure in XAMPP
```
C:\xampp\htdocs\php-crud-app\
├── config/           # Configuration files
├── database/         # Database schema
├── public/           # Document root (contains .htaccess)
│   ├── .htaccess     # URL rewriting rules
│   ├── index.php     # Main entry point
│   ├── api/          # API endpoints
│   ├── css/          # Stylesheets
│   └── js/           # JavaScript files
├── src/              # Application source code
├── vendor/           # Composer dependencies
├── .env              # Environment configuration
└── composer.json     # PHP dependencies
```

## Security Notes for Production

If you plan to deploy this application in a production environment:

1. Change the default admin password
2. Use a strong database password
3. Enable HTTPS
4. Restrict database access
5. Keep PHP and all dependencies updated
6. Review and harden Apache configuration