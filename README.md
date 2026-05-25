# Sumitra Shoes Centre - E-Commerce Project Report & Setup Guide

This is a complete, industry-standard E-Commerce Web Application for an online shoe store called **Sumitra Shoes Centre**. It has been fully engineered using **PHP** for the backend, **MySQL** for the database, and **HTML5, CSS3, and JavaScript** for a modern, responsive, and minimalist frontend.

## 🛠️ Project Architecture
The project follows a standard **Client-Server Architecture**:
1. **Frontend**: Client-side interface for customers to browse shoes, manage the cart, and securely checkout via Esewa or Khalti. Includes an interactive Admin Control Panel for catalog management.
2. **Backend**: Server-side PHP scripts managing session persistence, secure authentication (using strong blowfish password hashing), and transaction tracking.
3. **Database**: MySQL database structure storing relational entries for users, administrators, products, and historic order logs.

---

## 🗄️ Database Schema Design
The relational schema comprises 4 key tables detailed below. Paste this SQL in your phpMyAdmin to initialize the system.

### 1. `users` (Customer Accounts)
Stores customer profiles with salt-hashed passwords.
* `id` (INT, Primary Key, Auto Increment)
* `username` (VARCHAR(50), Unique)
* `email` (VARCHAR(100), Unique)
* `password` (VARCHAR(255)) - Encrypted via `password_hash()`

### 2. `admins` (System Admin Roles)
Stores administrative authentications for CRUD privileges.
* `id` (INT, Primary Key, Auto Increment)
* `username` (VARCHAR(50), Unique)
* `password` (VARCHAR(255)) - Encrypted

### 3. `products` (Shoe Inventory Catalog)
Stores footwear lines, prices, and visual media tags.
* `id` (INT, Primary Key, Auto Increment)
* `name` (VARCHAR(100))
* `price` (DECIMAL(10,2))
* `image` (VARCHAR(255)) - Path or URL to the shoe image
* `category` (VARCHAR(50)) - Sneakers, Sports, Formal, Casual

### 4. `orders` (Sales Transaction Records)
Tracks payment records, amounts, and Esewa/Khalti Transaction IDs.
* `id` (INT, Primary Key, Auto Increment)
* `user_id` (INT) - Foreign Key from `users.id`
* `items` (TEXT) - Serialized details or JSON string of bought items
* `total` (DECIMAL(10,2))
* `transaction_id` (VARCHAR(100)) - Transaction ID from eSewa/Khalti

---

## 🚀 Step-by-Step Local Setup Instructions

### Prerequisites
1. Install **XAMPP** (includes Apache Server & MySQL Database) or **WampServer**.
2. Download this project folder and extract it.

### Step 1: Place code in Local Server
Move the project folder into your Web Directory:
* On XAMPP: `C:/xampp/htdocs/sumitra_shoes/`
* On Mac/Linux: `/opt/lampp/htdocs/sumitra_shoes/`

### Step 2: Set Up MySQL Database
1. Launch **XAMPP Control Panel** and start **Apache** and **MySQL**.
2. Open your web browser and navigate to: `http://localhost/phpmyadmin`
3. Click on the **Databases** tab, type `sumitra_shoes_db` as the database name, and Click **Create**.
4. Click on the newly created database, open the **SQL** tab.
5. Copy and paste the complete content of `schema.sql` (provided in this project) and click **Go**. This creates the tables and inserts sample items (including admin credentials!).

### Step 3: Run the Website
Now go to your web browser and navigate to:
`http://localhost/sumitra_shoes/`
You can register a customer account or login using sample credentials!

### Admin Credentials (To test Admin Panel)
* **Username**: `admin`
* **Password**: `admin123`

---

## ⚡ Gateway Integrations (Esewa & Khalti)
* **eSewa Integration**: Operates via client post-callbacks towards eSewa's standard merchant sandbox gateway.
* **Khalti Integration**: Operates via an inline JavaScript handler communicating with the Khalti checkout CDN script, completing validations dynamically.
