# ⏱️ TTrack

A lightweight PHP-based time tracking application for managing work sessions, monitoring logged hours, and exporting timesheet data.

## Features

* User authentication and session management
* Track working hours and time entries
* Edit existing records
* Dashboard overview
* Export timesheets to CSV
* Account management
* REST-style API architecture
* MySQL database integration

---

## Tech Stack

| Technology          | Purpose                |
| ------------------- | ---------------------- |
| PHP                 | Backend                |
| MySQL / MariaDB     | Database               |
| JavaScript (jQuery) | Frontend Interactivity |
| HTML5 & CSS3        | User Interface         |
| Font Awesome        | Icons                  |

---

## Requirements

* PHP 8.0 or newer
* MySQL or MariaDB
* Apache with mod_rewrite enabled
* Write permissions for the application root directory

---

## Installation

Before installing, ensure your server meets the requirements:

### Download

Clone or download the repository and upload it to your web server.

* git clone https://github.com/your-username/your-repository.git
* Open your browser and navigate to your installation directory
* Enter your database connection details
* Click Install.

The installer will automatically:

Create the .env configuration file
Generate the required .htaccess file
Create the application database (if it does not already exist)
Create all required tables
After Installation

Then delete or rename the installer directory after installation.

Example:

mv installer installer_backup

or

rm -rf installer

Once the installer has been removed, refresh the application in your browser.

### Troubleshooting

Installer cannot write files

Ensure PHP has write permissions for the application root directory. The installer must be able to create:

.env
.htaccess
Database creation fails

Verify that the database user has sufficient privileges:

CREATE DATABASE | 
CREATE TABLE | 
ALTER | 
INSERT | 
UPDATE | 
DELETE | 
SELECT

Page returns 404 errors

Make sure Apache's mod_rewrite module is enabled and .htaccess files are allowed by your virtual host configuration.

Example Apache configuration:

<Directory "/var/www/html">
    AllowOverride All
</Directory>

## Project Structure

```text
Ttrack/
│
├── api/
│   ├── index.php
│   └── src/
│
├── assets/
│   ├── css/
│   └── js/
│
├── pages/
├── src/
│
├── index.php
├── login.php
├── router.php
├── .env.example
└── README.md
```

---

## API Overview

The API provides endpoints for:

* Authentication
* Session validation
* User management
* Time entry creation
* Time entry updates
* Timesheet retrieval
* CSV exports

## License

This project is licensed under the MIT License.

See the [LICENSE](LICENSE) file for details.

---

## Author

Developed by **Lagger2807**.
