# Developer Guide

This guide provides information for developers working on the E-Vaccination Appointment System.

## Project Structure

The project follows a standard PHP web application structure.

```
eproject-sem-2/
├── admin/             # Admin panel files
│   ├── admin_partials/  # Admin panel partials (header, footer)
│   └── ...
├── asset/             # CSS, JS, images, etc.
├── database/          # Database connection and SQL dump
│   ├── db_conn.php
│   └── vaccination_db.sql
├── partials/          # General partials (header, footer)
├── add_child.php
├── ...
└── register.php
```

## Database Schema

The database `vaccination_db` consists of the following main tables:

*   `admins`: Stores administrator accounts.
*   `parents`: Stores parent user accounts.
*   `childrens`: Stores children's information, linked to parents.
*   `hospitals`: Stores hospital information and accounts.
*   `vaccines`: Stores vaccine information, linked to hospitals.
*   `vaccination_schedules`: Stores appointment information, linking parents, children, hospitals, and vaccines.
*   `roles`: Defines user roles (Super Admin, Admin, Hospital, Parent).
*   `inquiries`: Stores contact/inquiry form submissions.

### Key Relationships

*   A `parent` can have multiple `childrens`.
*   A `hospital` can have multiple `vaccines`.
*   A `vaccination_schedules` entry links a `child`, `parent`, `hospital`, and `vaccine` together to form an appointment.
*   `admins`, `parents`, and `hospitals` are assigned a `role_id` from the `roles` table.

## Core Logic

*   **Database Connection:** The database connection is managed in `database/db_conn.php`. This file is included in other files that need database access.
*   **User Authentication:**
    *   `login.php`: Handles login for all user types.
    *   `register.php`: Handles registration for parents and hospitals.
    *   `logout.php`: Destroys the session and logs the user out.
*   **Admin Panel:** The `admin/` directory contains the logic for the admin dashboard, including user management, appointment viewing, etc.
*   **Parent-Specific Actions:**
    *   `parents_book_appointment.php`: Logic for booking an appointment.
    *   `add_child.php`: Logic for adding a child.
    *   `parents_appointments.php`: Viewing appointments.
*   **Hospital-Specific Actions:**
    *   `hospital_inven.php`: Hospital's vaccine inventory management.
    *   `hospital_add_vacc.php`: Logic for adding a new vaccine.
    *   `hospital_appointment.php`: Viewing appointments for the hospital.

## How to Contribute

1.  **Follow the existing coding style.**
2.  **Make sure your changes do not introduce new security vulnerabilities.** (e.g., use prepared statements to prevent SQL injection).
3.  **Test your changes thoroughly before submitting them.**
4.  **Update the documentation if you add new features.**
