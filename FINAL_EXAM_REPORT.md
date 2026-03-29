# FINAL PROJECT REPORT: Campus Lost & Found System

## Project Information
**Project Title:** Campus Lost & Found Tracker
**Developer:** Kamwanga Rahiim
**Registration Number:** JAN23/BSE/2177U
**Framework:** Laravel 11 (PHP 8)
**Submission For:** Final Exam

---

## 1. Executive Summary
This application is a comprehensive Campus Lost & Found tracker designed to help students and staff securely report and recover missing items. Over the testing cycles, the project has evolved from a basic CRUD application into a robust, secure, and fully-featured system. For the Final Exam submission, the focus was placed heavily on **advanced technical architecture, modernized UI presentation, strict data privacy, and automated communications**.

## 2. Advanced Functionality Additions (Final Exam Enhancements)

### A. Privacy-First Authentication & Authorization
* **Why it was added:** In earlier versions, any user could edit or delete any item, and phone numbers were exposed publicly. This was a severe privacy risk.
* **How it was implemented:** 
  * Integrated **Laravel Auth / Session Management**.
  * Dropped the public `contact` column from the database entirely.
  * Implemented **Laravel Gates (`Gate::authorize()`)** to strictly ensure that only the user who created an item can Edit, Delete, or Mark it as Claimed. 
  * Other users now interact by clicking a secure "Email Reporter" link.

### B. Asynchronous Background Queues (Image Processing)
* **Why it was added:** Uploading large high-resolution images synchronously caused the website to freeze or timeout for users with slow campus internet.
* **How it was implemented:** 
  * Added the `Intervention\Image` library and implemented a **Queued Job (`ProcessItemPhoto`)**. 
  * Now, when a user uploads a photo, the raw file is saved instantly, and an asynchronous background worker takes over to safely compress the image to `WebP` (1200x1200) format. This drastically reduces page load times and server storage costs without impacting user experience.

### C. Automated Email Notifications
* **Why it was added:** Users needed instant positive feedback when a successful resolution occurred.
* **How it was implemented:** Added Laravel **Notifications (`ItemClaimedNotification`)**. When an item's status is patched to "Claimed", the system automatically dispatches an email to the original reporter thanking them for their contribution to the campus community and providing a final record link.

---

## 3. UI/UX and Presentation Refinements
Following feedback to improve presentation, the frontend (powered by Blade and TailwindCSS) was significantly refined:
1. **Redesigned Item Cards:** Adjusted the dashboard grids to cleanly display the Reporter's Avatar/Name, Location, and Status badging without looking cluttered. Action buttons (Edit/Claim/Delete) were converted into clean iconography to save space.
2. **Modernized Empty States:** If a search returns no results, the user is now met with a friendly, beautifully illustrated "No Items Found" component with direct action links.
3. **Responsive Details View:** The Item detail page (`show.blade.php`) was overhauled to feature a sticky sidebar gallery, improved status timeline visualizer, and a prominent call-to-action outbox to securely connect context users.

---

## 4. Software Architecture Breakdown (MVC+)
The application strictly follows advanced Laravel design patterns:
* **Controllers (`ItemController`):** Kept extremely thin. Only handles parsing HTTP Requests, calling the Gate for permissions, invoking the Service, and returning Redirects/Views.
* **Services (`ItemService`):** Centralized all business logic (e.g., handling database Transactions, locking rows via `lockForUpdate()` to prevent race conditions during claims).
* **Form Requests:** Reusable validation classes (`StoreItemRequest`) isolate validation rules from controllers.
* **Tests (PHPUnit):** A heavily customized Test Suite (`ItemFeatureTest`) was built using `RefreshDatabase` and `actingAs()` to guarantee the safety of the application logic before any deployment.

---

## 5. Cleaning the Submission Final Package
To ensure the project is sterile and production-ready for evaluation, the codebase has been purged of unneeded logs, caches, hidden Byte Order Marks (BOM), and development boilerplate. 

---
**Thank you for your guidance throughout this course!**
