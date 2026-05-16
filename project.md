# Internal Audit Management System — Improved Requirement Draft

## Overview

The Internal Audit Management System is a web-based application designed to manage audit activities across departments and projects within an organization. The system allows administrators to create projects, assign auditors to audit events, collect audit scores/comments, and generate analytical reports and dashboards.

---

# User Roles

## 1. Administrator

The Administrator has full system access and can:

- Manage departments
- Create and manage projects
- Create and manage users
- Assign users to audit events
- View audit reports and analytics
- Monitor project performance and auditor performance
- Manage system settings and statuses

---

## 2. Super User

The Super User has advanced operational permissions but limited system configuration access.

Capabilities:

- View assigned projects
- Manage audit events
- Review submitted audit results
- Generate reports
- Monitor audit progress

---

## 3. Normal User (Auditor)

Normal users are auditors assigned to audit events.

Capabilities:

- Receive audit event notifications
- View assigned audits
- Submit audit scores
- Add comments and findings
- View personal audit history

---

# Core Modules

# 1. Department Management

Administrator can:

- Create department
- Edit department
- Activate/deactivate department
- View department statistics

### Department Fields

- Department ID
- Department Name
- Description
- Status

---

# 2. User Management

Administrator can create and manage users.

### User Fields

- User ID
- User Code
- Full Name
- Gender
- Role
- Password
- Department
- Email
- Phone Number
- Status (Active/Inactive)
- Created Date

### User Roles

- Administrator
- Super User
- Normal User

---

# 3. Project Management

Administrator can create and manage projects.

### Project Fields

- Project ID
- Project Code
- Project Name
- Project Manager (PM)
- Location
- Department
- Start Date
- End Date
- Status

---

# 4. Audit Event Management

Administrators or Super Users can create audit events and assign auditors.

### Audit Event Features

- Select project
- Assign one or multiple auditors
- Set audit schedule (date and time)
- Set audit type
- Add audit checklist/questions
- Track audit status

### Audit Event Fields

- Audit Event ID
- Audit Title
- Project
- Audit Date
- Audit Time
- Assigned Users
- Description
- Status
    - Pending
    - In Progress
    - Completed

---

# 5. Notification System

When a user is assigned to an audit event:

- A popup notification automatically appears in the user dashboard
- Optional email notification
- Optional in-app notification bell

Notification includes:

- Project Name
- Audit Date & Time
- Audit Location
- Assigned By

---

# 6. Audit Submission Module

Assigned users can submit audit evaluations.

### Audit Submission Features

- Score input
- Comment submission
- Attach evidence/photos/documents
- Save draft
- Final submit

### Audit Result Fields

- Audit Event
- Auditor Name
- Score
- Comment
- Attachment
- Submitted Date

---

# 7. Dashboard & Analytics

## Administrator Dashboard

### General Statistics

- Total Departments
- Total Projects
- Total Users
- Total Audit Events
- Completed Audits
- Pending Audits

---

## Audit Analytics

### Project Audit Report

Display:

- All scores from auditors
- Average project score
- Highest and lowest score
- Audit comments/history

### Department Performance

Display:

- Average score by department
- Number of audits completed
- Auditor participation rate

### Auditor Performance

Display:

- Average score submitted by each auditor
- Total audits completed
- Audit consistency

---

# 8. Reports

System should generate:

- Audit Summary Report
- Department Audit Report
- Project Performance Report
- Auditor Activity Report

Export options:

- PDF
- Excel
- CSV

---

# 9. Suggested Additional Features

## Recommended Improvements

### Audit Checklist Templates

Create reusable templates for different audit types.

### Real-Time Dashboard Charts

Use charts for:

- Average scores
- Audit trends
- Department comparison

### Role-Based Access Control (RBAC)

More secure permission management.

### Audit Evidence Upload

Allow image/PDF uploads as proof.

### Audit History Timeline

Track all audit activities chronologically.

### Mobile Responsive UI

Support tablets and phones for field auditors.

### Digital Signature

Auditors and managers can digitally sign reports.

### Auto Score Calculation

Automatically calculate:

- Average scores
- Department averages
- Overall project ratings

---

## Backend

- Laravel

## Frontend

- Blade

## Database

- MySQL

## Authentication

- Laravel Breeze

## Notifications

- WebSocket

---

# Suggested Database Tables

- departments
- users
- projects
- audit_events
- audit_assignments
- audit_questions
- audit_results
- notifications
- attachments

---

# Workflow Summary

1. Administrator creates departments and projects
2. Administrator creates users
3. Administrator creates audit event
4. Auditors are assigned to event
5. User receives popup notification
6. Auditor submits score and comments
7. System calculates averages
8. Dashboard updates analytics and reports automatically
