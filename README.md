# Criminal Management System

The Criminal Management System is a web-based application designed to streamline the management of criminal records and the assignment of investigators to cases. This system aids law enforcement agencies in efficiently collecting evidence and enhancing public safety.

## Features

- **Criminal Record Management:** Store and manage detailed information about criminals, including personal details, criminal history, and photographs.
- **Investigator Assignment:** Assign investigators to specific cases, facilitating organized and efficient case management.
- **Case Management:** Add, update, and delete case details, ensuring accurate and up-to-date records.
- **Crime Statistics:** Generate and view crime statistics to analyze crime patterns and trends.
- **User Management:** Manage user roles and permissions to ensure secure access to the system.

## Technologies Used

- **Front-End:** HTML5, CSS3, JavaScript
- **Back-End:** PHP
- **Database:** MySQL

## Installation

To set up the Criminal Management System on your local machine, follow these steps:

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/Sangwani-J0E/Criminal-Management-System.git
   ```

2. **Move the Cloned Repository:**

   Copy the cloned folder to your web server's root directory. For example, if you're using XAMPP, place it in the `htdocs` folder.

3. **Start the Web Server and Database:**

   Ensure that your web server (e.g., Apache) and database server (e.g., MySQL) are running.

4. **Configure the Database:**

   - Open your web browser and navigate to `http://localhost/phpmyadmin`.
   - Create a new database (e.g., `criminal_management`).
   - Import the provided SQL file (`database/criminal_management.sql`) into the newly created database.

5. **Configure the Application:**

   - Open the `config.php` file located in the project's root directory.
   - Update the database configuration settings (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`) to match your environment.

6. **Access the Application:**

   Open your web browser and navigate to `http://localhost/Criminal-Management-System`.

## Usage

- **Admin Dashboard:** Access administrative functionalities such as user management, case assignment, and viewing crime statistics.
- **Investigator Dashboard:** Investigators can view assigned cases, update case statuses, and manage evidence.
- **Crime Records:** Add, edit, search, and delete criminal records as needed.
