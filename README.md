
# 🕰️ **Clock Clicker Game**

A fun and simple clock-based game where you click the clock as many times as possible to earn points.

---

## Installation and Setup 🚀

### 1. **Download and Install XAMPP**
- Go to [XAMPP official website](https://www.apachefriends.org/index.html) and download the latest version for your operating system (Windows, macOS, or Linux).
- Follow the installation steps to set up XAMPP on your machine.

### 2. **Create a MySQL Database**
- Open **phpMyAdmin** by navigating to `http://localhost/phpmyadmin` in your browser. (Default credentials: username = `root`, password = blank.)
- Click on **Databases** and then click **New** to create a new database.
- Name the database `clock_clicker_db`.

### 3. **Import the SQL Dump**
- In **phpMyAdmin**, select the `clock_clicker_db` database you just created.
- Click on the **Import** tab.
- Click **Choose File** and select the `clock_clicker.sql` file from your project directory.
- Click **Go** to import the database schema and required tables.
- The import will create all the necessary tables (e.g., `users`, `leaderboard`) and setup the database structure.

### 4. **Create a MySQL User for the Database**
- In **phpMyAdmin**, go to the **Privileges** tab for `clock_clicker_db`.
- Click **Add user** and fill in the following information:
  - **Username**: `clock_clicker_user`
  - **Password**: Enter your desired password
  - **Privileges**: Select **ALL PRIVILEGES** for `clock_clicker_db`.

### 5. **Access Your Website**
- Open a browser and go to `http://localhost/clockclicker/` (assuming the website folder is named `clockclicker`).
- You should now see the **Clock Clicker Game** in action!

---

## Configuring the Website ⚙️

### 1. **Update the `cfg.php` File**
- Navigate to the root directory of your XAMPP installation and find the `cfg.php` file.
- Open it and update the following lines with your database connection details:

   ```php
   $host = 'localhost';
   $dbname = 'clock_clicker_db';
   $username = 'clock_clicker_user';
   $password = 'your_desired_password';
   ```

### 2. **Run the Website**
- Open your browser and go to `http://localhost/clockclicker/`.
- The **Clock Clicker Game** should now be live with the new database connection!

---

## Troubleshooting 🛠️

- If you encounter any issues during setup, refer to the [XAMPP documentation](https://www.apachefriends.org/faq.html) or search online for solutions.
- Double-check that the `clock_clicker_user` has the correct privileges on the `clock_clicker_db` database.
- If you experience database connection errors, verify that the credentials in the `cfg.php` file match your database settings.

---

## Batch Account Creation 🧑‍💻

You can quickly generate fake user accounts for testing purposes using the `!batch_account_creation.php` script. This script will create a specified number of random users and insert them into the **users** and **leaderboard** tables.

---

### Enjoy the Game! 🎮

---
