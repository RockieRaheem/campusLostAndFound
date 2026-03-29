# FINAL PROJECT REPORT: Campus Lost & Found System

## Project Information
**Project Title:** Campus Lost & Found Tracker
**Developer:** Kamwanga Rahiim
**Registration Number:** JAN23/BSE/2177U
**Framework:** Laravel 11 (PHP 8)
**Submission For:** Final Exam

---

## 1. Addressing Lecturer Feedback & Previous Iterations
*In response to the feedback from the previous submission, significant efforts were made to clearly articulate the system's enhancements, refine the user interface, implement advanced functionalities, and package the project professionally.*

### Clear Explanation of Early Enhancements (Test 1 & 2 Evolution)
- **Search and Filtering:** Implemented a robust query system using Laravel's Eloquent ORM to allow users to search by keyword, location, and filter by "Lost", "Found", or "Claimed" statuses. This eliminated the need to scroll endlessly through pagination.
- **Claimed Status Tracking:** Introduced a claimed_at timestamp. Instead of simply deleting recovered items, the system archives them visually, building a historical ledger of successful recoveries.
- **Comprehensive Workflow & Item Details:** Designed a dedicated show.blade.php view. Users can now click into an item to see high-resolution images, descriptive timelines, and the original reporting user, moving away from a cluttered main dashboard.

## 2. Advanced Functionality Additions (Final Exam Enhancements)

### A. Role-Based Authorization & Separation of Concerns (New Feature)
- **Concept:** The system needed to function accurately for different types of users in a real-world scenario (Reporters vs. Discoverers).
- **Implementation:** Re-architected Laravel Gates via ItemPolicy.php.
  - **Creators (Owners):** Can Edit, Delete, and Manage their own items.
  - **Other Users:** Cannot Edit/Delete items they didn't post. However, they *can* interact with the system by marking an item as "Claimed" if they are retrieving it. This elevates the application's logic to industry-standard peer-to-peer security levels.

### B. Interactive Claimant Information Tracking (New Feature)
- **Concept:** Marking an item as "Claimed" previously offered no audit trail. 
- **Implementation:** Added a claimant_info column to the database via migration. The user interface now utilizes Javascript interactive prompts (prompt()) appended to hidden form inputs to safely capture the person recovering the item (e.g., Student ID, Name) without breaking the page workflow. This data is verified and rendered beautifully into the Item Detail cards.

### C. Automated Email Notifications
- **Concept:** Closing the communication loop seamlessly upon a successful interaction.
- **Implementation:** Designed a background Laravel Notification system (ItemClaimedNotification). When a user claims an item, the original poster is automatically dispatched an email alert thanking them for their contribution and notifying them of the resolution.

## 3. UI/UX and Presentation Refinements
Following the directive to heavily improve presentation, the frontend (powered by Blade and TailwindCSS) underwent a massive polish:
1. **Iconography & Density:** Converted bulky text Action buttons (Edit/Claim/Delete) into clean Material Symbols. The dashboard grids safely trim long text overflowing and handle missing user data with clean fallbacks (e.g., 'Anonymous').
2. **Modernized Empty States:** If a search returns no results, the user is greeted with a beautifully illustrated, centered "No Items Found" interface element, replacing standard dull text strings and improving user retention.
3. **Dynamic Display Contexts:** The Item Details UI dynamically splits its CSS grid to accommodate the new "Claimed By" identification badging, conditionally rendering strictly if the data exists.

## 4. Software Architecture Breakdown (MVC+)
The application strictly respects advanced Laravel design patterns:
* **Controllers (ItemController):** Kept razor-thin. Handles parsing HTTP Requests, calling the Gate/Policies for strict permissions, invoking Services, and returning Views.
* **Services (ItemService):** Centralizes all heavy business logic (e.g., handling secure data arrays, and dispatching notifications).
* **Tests (PHPUnit):** A heavily customized Test Suite (ItemFeatureTest) using inline RefreshDatabase ensures a 100% pass rate. Deep-rooted Byte Order Mark (BOM) file corruption issues were surgically identified and removed from the core environment.

## 5. Clean Submission Package
To ensure the project is sterile, strictly organized, and easily deployable for final evaluation, a custom PowerShell build script (prepare-submission.ps1) was authored. This seamlessly purges local application caches, removes hidden generated boilerplate, and builds a pristine ZIP artifact for grading.

---
**Thank you for your guidance throughout this course!**
