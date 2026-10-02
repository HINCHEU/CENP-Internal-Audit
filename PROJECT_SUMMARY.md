# CENP Internal Audit Management System

## 1. Overview
The **CENP Internal Audit Management System** is a robust web-based application designed to streamline, track, and manage audit activities across various departments and projects within an organization. It provides a centralized platform for administrators to orchestrate audits, and for auditors to submit evaluations efficiently.

## 2. Technology Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Laravel Blade, Vite (for asset bundling)
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **Key Dependencies**: 
  - `maatwebsite/excel` (Excel report generation)
  - `laravel/pail`, `laravel/pint`, `laravel/sail` (Development utilities)

## 3. User Roles & Permissions
The system utilizes Role-Based Access Control (RBAC) with three primary tiers:

* **Administrator**: Has full system control. Can manage departments, projects, users, system settings, and oversee all audit analytics.
* **Super User**: Handles advanced operational tasks. Can manage audit events, review submitted results, and generate reports, but lacks system configuration access.
* **Normal User (Auditor)**: Can view assigned audits, submit audit scores and findings, attach evidence, and view their personal audit history.

## 4. Core Modules
- **Department & User Management**: CRUD operations for organizational departments and user accounts.
- **Project Management**: Track projects including their managers, locations, dates, and statuses.
- **Audit Event Management**: Scheduling and assigning auditors to specific projects with defined checklists.
- **Notification System**: Real-time popups and alerts for assigned audits.
- **Audit Submission**: Interfaces for auditors to input scores, attach documentary evidence, and save drafts before final submission.
- **Dashboards & Analytics**: Real-time statistics detailing project performance, department averages, and auditor consistency.
- **Reporting**: Exportable reports (PDF, Excel, CSV) summarizing audit activities and performance metrics.

## 5. Local Development Setup

To run the project locally, ensure you have PHP 8.2+, Composer, Node.js, and MySQL installed.

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Update your `.env` file with the correct `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`.*

3. **Database Migration**
   ```bash
   php artisan migrate
   ```

4. **Run Development Servers**
   Open two terminals to run the backend and frontend concurrently:
   ```bash
   # Terminal 1
   php artisan serve

   # Terminal 2
   npm run dev
   ```
   *The application will be accessible at `http://localhost:8000`.*
