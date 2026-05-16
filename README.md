# Internal Audit Management System

## Overview

The Internal Audit Management System is a web-based application designed to manage audit activities across departments and projects within an organization. The system allows administrators to create projects, assign auditors to audit events, collect audit scores/comments, and generate analytical reports and dashboards.

---

## User Roles

### 1. Administrator

The Administrator has full system access and can:

- Manage departments
- Create and manage projects
- Create and manage users
- Assign users to audit events
- View audit reports and analytics
- Monitor project performance and auditor performance
- Manage system settings and statuses

### 2. Super User

The Super User has advanced operational permissions but limited system configuration access.

- View assigned projects
- Manage audit events
- Review submitted audit results
- Generate reports
- Monitor audit progress

### 3. Normal User (Auditor)

Normal users are auditors assigned to audit events.

- Receive audit event notifications
- View assigned audits
- Submit audit scores
- Add comments and findings
- View personal audit history

---

## Core Modules

### 1. Department Management

Administrator can create, edit, activate/deactivate departments and view department statistics.

### 2. User Management

Administrator can create and manage users with roles (Administrator, Super User, Normal User).

### 3. Project Management

Administrator can create and manage projects with details like Manager, Location, Department, Start/End Dates, and Status.

### 4. Audit Event Management

Administrators or Super Users can create audit events, select projects, assign one or multiple auditors, set audit schedule/type/checklist, and track status.

### 5. Notification System

When a user is assigned to an audit event:

- A popup notification automatically appears in the user dashboard
- Optional email notification and in-app notification bell
- Includes: Project Name, Audit Date & Time, Audit Location, Assigned By

### 6. Audit Submission Module

Assigned users can submit audit evaluations:

- Score input
- Comment submission
- Attach evidence/photos/documents
- Save draft & Final submit

### 7. Dashboard & Analytics

Provides general statistics and audit analytics including project audit reports, department performance, and auditor performance.

### 8. Reports

System should generate:

- Audit Summary Report
- Department Audit Report
- Project Performance Report
- Auditor Activity Report

Export options: PDF, Excel, CSV

---

## Tech Stack

- **Backend**: Laravel
- **Frontend**: Blade
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **Notifications**: WebSocket

---

## Workflow Summary

1. Administrator creates departments and projects
2. Administrator creates users
3. Administrator creates audit event
4. Auditors are assigned to event
5. User receives popup notification
6. Auditor submits score and comments
7. System calculates averages
8. Dashboard updates analytics and reports automatically

---

## How to Run the Project

### Prerequisites

- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL

### Installation Steps

1. **Clone the repository (or navigate to the project directory):**

    ```bash
    cd CENP-Internal-Audit
    ```

2. **Install PHP dependencies:**

    ```bash
    composer install
    ```

3. **Install JavaScript dependencies:**

    ```bash
    npm install
    ```

4. **Copy the environment file:**

    ```bash
    cp .env.example .env
    ```

5. **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

6. **Configure the Database:**
   Open the `.env` file and update your database credentials:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```

7. **Run database migrations (and seeders if applicable):**

    ```bash
    php artisan migrate
    ```

    _Note: If you have seeders to populate initial data, you can run `php artisan migrate --seed` instead._

8. **Run the local development server:**
   You will need to run both the Laravel backend server and the Vite frontend server simultaneously. Open two separate terminals:

    **Terminal 1 (Backend):**

    ```bash
    php artisan serve
    ```

    **Terminal 2 (Frontend):**

    ```bash
    npm run dev
    ```

9. **Access the application:**
   Open your browser and navigate to `http://localhost:8000`.
