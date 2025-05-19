# KinderWise

**KinderWise** is a Kindergarten Assessment Management System that streamlines academic and administrative tasks by connecting administrators, principals, teachers, and parents through a unified platform.

---

## 📌 Overview

The KinderWise System is designed to streamline kindergarten assessment management operations by enabling stakeholders to perform essential tasks efficiently:

* **Administrators** manage user accounts, configure system settings, and access all data.
* **Principals** monitor student performance, oversee teacher inputs, and verify semester-end results.
* **Teachers** handle assessments and grading, track student progress, and post announcements.
* **Parents** view their children’s progress reports and receive updates from teachers.

These interconnected functionalities ensure seamless collaboration while promoting effective academic and administrative operations within the kindergarten.

---

## ✅ Features

* 🧑‍🏫 **Role-Based Access Control**: Distinct interfaces and privileges for admins, principals, teachers, and parents.
* 📊 **Assessment & Grading**: Teachers can create and manage assessments, and input grades.
* 📈 **Performance Tracking**: Principals and teachers can track student progress across the semester.
* 📢 **Announcements**: Teachers can post updates that parents can view in real time.
* 📋 **Reports**: Generate performance reports accessible by both school staff and parents.
* 🔒 **Authentication**: Secure login system with user session handling.
* 🖥️ **User-Friendly Interface**: Clean, accessible UI designed for ease of use.

---

## 🛠 Technologies Used

* **Backend**: PHP (with MySQL integration)
* **Frontend**: HTML, CSS, JavaScript
* **Database**: MySQL
* **Version Control**: Git & GitHub

---

## 🚀 Getting Started

### Prerequisites

* XAMPP / WAMP or any local PHP server
* MySQL database
* Git

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/syuennl/kinderwise.git
   ```

2. Place the project folder into your web server's root directory (e.g., `htdocs` for XAMPP).

3. Import the provided `.sql` file into your MySQL database using phpMyAdmin or any SQL client.

4. Configure your database connection in the project’s configuration file (e.g., `config.php` or similar).

5. Start your local server and access the system via browser (e.g., `http://localhost/kinderwise/`).

---

## 👤 User Roles & Access

| Role          | Capabilities                                                          |
| ------------- | --------------------------------------------------------------------- |
| Administrator | User management, system configuration, full data access               |
| Principal     | View all student data, verify teacher inputs and semester-end results |
| Teacher       | Manage assessments, grade students, post announcements                |
| Parent        | View children’s progress and updates                                  |

---

