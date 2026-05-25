# Tripistry 

## Installation and Execution guide:

### Prerequisites
Make sure you have a working server using localhost that includes an Apache HTTP Server, PHP, MariaDB/MySQL

### Step 1:
Clone this repository inside your server's root directory (usually located in C:/xampp/htdocs/ if you are using XAMPP)

### Step 2:
Create a file named ".env" and fill in your local database credentials
(Please see the .env.example)

### Step 3:
1. Open your favourite browser and navigate to your database management UI (usually http://localhost/phpmyadmin/)
2. Create a new database named "u24916031_tripistry"
3. Click on "Import".
4. Click "Choose File" and select the dump file in our directory: u24916031_tripistry.sql
5. Click on Go/Import - this sets up all the tables needed.

### Step 4:
1. Make sure that Apache and MySQL/MariaDB Modules are active
2. Open your web browser and navigate to the local directory: http://localhost/tripistry/index.html
3. Register a new account and browse Tripistry as either a traveller or a travel agency.
