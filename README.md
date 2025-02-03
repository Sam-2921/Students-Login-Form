# Student Login System 🎓

## Overview
**Student Login System** is a PHP-based web application that allows students to register, log in, and manage their information securely. It supports role-based authentication, user data modification, and soft deletion of records.

## Features 🚀
- **Secure Login System** – Students can log in using their Admission ID and Name.
- **Admin Access** – Special login for Admin to manage all student records.
- **Student Registration** – New users can sign up with their details.
- **Edit & Update Records** – Modify student details securely.
- **Soft Delete** – Marks student records as deleted instead of removing them permanently.
- **Responsive UI** – Styled with Bootstrap for a clean and mobile-friendly design.

## Technologies Used 🛠️
- **Frontend:** HTML, CSS, Bootstrap
- **Backend:** PHP
- **Database:** MySQL

## Installation ⚙️
Follow these steps to set up the project locally:

1. Clone the repository:
   ```sh
   git clone https://github.com/your-username/student-login.git
   cd student-login
   ```
2. Set up a MySQL database and import the `database.sql` file.
3. Configure the database connection in the `config.php` file:
   ```php
   $conn = new mysqli("localhost", "your_username", "your_password", "registration_db");
   ```
4. Start a local server using XAMPP or WAMP.
5. Open your browser and navigate to:
   ```
   http://localhost/student-login
   ```

## Usage 📌
1. **Student Login:** Enter Admission ID and Name to log in.
2. **Admin Login:** Use Admission ID `1234567890` and Name `Admin` for admin access.
3. **Register New Student:** Sign up with Admission ID, Name, and Contact Details.
4. **Edit Student Details:** Modify records via the admin panel.
5. **Delete Records:** Admin can soft-delete student records.

## Contribution 🤝
If you'd like to contribute:
1. Fork the repository.
2. Create a new branch (`feature-branch`).
3. Make your changes and commit.
4. Submit a pull request.

## License 📜
This project is licensed under the MIT License.

---

Made with ❤️ by **[R. Samson](https://www.linkedin.com/in/samson-r-525597253)**
