# Implement Quick Evaluation Feature

This plan outlines the addition of the "Quick Evaluation" feature, including public user registration, evaluation management, and specialized analytics.

## User Review Required

> [!IMPORTANT]
> **User Approval Workflow:** I will add an `is_approved` boolean column to the `users` table. New public registrations will default to `false`. They will not be able to log in until an Administrator edits their profile and sets them to approved. Does this sound correct?
> **Inhouse vs External:** Is the "inhouse" or "external" designation selected by the evaluator **each time they submit a score**, or should it be a fixed attribute on their **user profile**? The plan assumes it is selected upon score submission as requested ("have option to choose inhouse or external").
> **Grading and Analytics:** The plan calculates the project's overall score by taking the average of ALL "inhouse" scores to count as a *single* score, and then averaging that value with each individual "external" score. Example: 3 in-house scores (80, 90, 100 -> avg 90) and 2 external scores (85, 95). Final Average = (90 + 85 + 95) / 3 = 90. Is this the exact logic you want?

## Proposed Changes

### Database & Migrations
- Create migration to add `is_approved` (boolean, default true for existing) to `users` table.
- Create `evaluations` table (id, title, description, status, created_at, updated_at).
- Create `evaluation_scores` table (id, evaluation_id, user_id, evaluator_type (enum: inhouse, external), score, comment, created_at, updated_at).

### Models
- Update `User` model to include `is_approved` fillable and an `EvaluationScore` relationship.
- Create `Evaluation` model.
- Create `EvaluationScore` model.

### Controllers & Routes
- **AuthController**: Update to handle public registration (`/register`) and block login if `is_approved` is false.
- **EvaluationController** (Admin): CRUD for Quick Evaluations (No auditor pre-assignment required).
- **UserEvaluationController** (User): View available Quick Evaluations and submit scores with "inhouse" or "external" selection.
- **Analytics**: Update or create a dashboard section to calculate Evaluation grading.

### Views (Frontend)
- **Sidebar**: Add "Evaluations" tab under Admin Menu (for management) and Auditor Workspace (for scoring).
- **Public Registration**: Create `register.blade.php` with fields for Name, Email, Password, etc.
- **Admin Users**: Add a badge/toggle in the User Management list for "Pending Approval" vs "Approved".
- **Evaluation Forms**: 
  - Admin view: Create Evaluation form.
  - User view: Score submission form with radio button for In-house / External.
- **Analytics**: Display grading and calculated average (inhouse averaged as 1 + each external).

## Verification Plan

### Manual Verification
- Register a new user, verify they cannot log in immediately.
- Approve the user as Admin, verify successful login.
- Create an Evaluation as Admin.
- Log in as the new user, submit a score as "inhouse".
- Log in as another user, submit a score as "external".
- Check the Analytics tab to ensure the average calculation logic behaves perfectly as described.
