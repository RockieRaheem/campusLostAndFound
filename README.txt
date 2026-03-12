# Campus Lost & Found Tracker

## Project Information
- **Project Title:** Campus Lost & Found Tracker
- **Developer:** Kamwanga Rahiim
- **Registration Number:** JAN23/BSE/2177U
- **Framework:** Laravel (PHP)
- **Database:** MySQL

## Description
The Campus Lost & Found Tracker is a simple Laravel web application that allows university students to report lost or found items. The system stores the data in a MySQL database and displays all submitted records in a structured table format.

## Features
- Report lost or found items
- View all reported items with search and filter
- Search items by name, description, or location
- Filter items by status (Lost, Found, Claimed)
- Mark items as "Claimed" when recovered
- Delete items from the system
- Input validation on all forms
- Store and retrieve data from database
- Modern responsive design with card-based layout
- Real-time statistics dashboard

## Technologies Used
- PHP
- Laravel Framework
- MySQL Database
- XAMPP (Apache & MySQL)
- HTML/CSS

## Requirements
- PHP 8.2 or higher
- Composer
- MySQL Database
- XAMPP (or any PHP development environment)

## Installation & Setup

### Step 1: Install XAMPP
Download and install XAMPP from https://www.apachefriends.org/

### Step 2: Start Services
1. Open XAMPP Control Panel
2. Start Apache
3. Start MySQL

### Step 3: Create Database
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database named: `campus_lostfound`

### Step 4: Install Dependencies
Open terminal in the project folder and run:
```
composer install
```

### Step 5: Configure Environment
The `.env` file is already configured for local development:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campus_lostfound
DB_USERNAME=root
DB_PASSWORD=
```

### Step 6: Generate Application Key
```
php artisan key:generate
```

### Step 7: Run Migrations
```
php artisan migrate
```

### Step 8: Start the Server
```
php artisan serve
```

### Step 9: Access the Application
Open your browser and visit:
```
http://127.0.0.1:8000
```

## Project Structure
```
campus-lostfound/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── ItemController.php    # Handles item operations
│   └── Models/
│       └── Item.php                   # Item model (OOP class)
├── database/
│   └── migrations/
│       └── 2026_02_12_000000_create_items_table.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php          # Main layout
│       └── items/
│           ├── index.blade.php        # List all items
│           └── create.blade.php       # Form to report item
├── routes/
│   └── web.php                        # Application routes
└── .env                               # Environment configuration
```

## Object-Oriented Concepts Used

### 1. Classes and Objects
The `Item` model represents a class in Laravel. Each record stored in the database is treated as an object of the Item class.

### 2. Encapsulation
Encapsulation is implemented using the `$fillable` property in the model. This protects the attributes from mass assignment vulnerabilities.

### 3. Reusability
The controller methods are reusable. The `store()` method handles saving data while the `index()` method retrieves and displays data.

## Submission Instructions (IMPORTANT)

Before submitting this project, make sure to:

1. **REMOVE the .git folder** - This folder should NOT be included in your submission
   - Windows: Delete the `.git` folder manually (it may be hidden)
   - Or run in terminal: `rmdir /s /q .git`
   
2. **REMOVE the vendor folder** - Run `composer install` to regenerate it
   
3. **ZIP file structure should be:**
   ```
   Source_Code/
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── database/
   ├── public/
   ├── resources/
   ├── routes/
   ├── storage/
   ├── tests/
   ├── .env.example
   ├── composer.json
   ├── README.txt
   └── (other files, but NOT .git or vendor)
   ```

## Contact
For any questions, please contact: Kamwanga Rahiim
