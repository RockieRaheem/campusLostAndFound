# Campus Lost & Found System - Presentation Script

## Slide 1: Title Slide

- **Title**: Campus Lost & Found Tracker
- **Subtitle**: Final Project Presentation
- **Student Name**: Kamwanga Rahiim
- **Registration Number**: JAN23/BSE/2177U

## Slide 2: Problem Statement

- **The Issue**: Campuses lack an organized, secure, and automated way to reunite students with their lost items.
- **Current State**: Manual noticeboards are inefficient, lack searchability, and expose student contact details to the public, risking privacy.

## Slide 3: Objectives

- Develop a secure, role-based platform for reporting Lost and Found items.
- Implement automated reunification features (Smart Matching) to reduce manual searching.
- Protect user privacy while allowing seamless communication between discoverers and owners.

## Slide 4: Technologies Used

- **Backend**: Laravel 11 framework (PHP 8)
- **Frontend**: Blade Templating Engine, TailwindCSS, vanilla JavaScript
- **Database**: MySQL / SQLite (via Eloquent ORM)
- **Architecture**: Model-View-Controller (MVC) + Service Layer Pattern

## Slide 5: System Overview

- A full-stack web application that manages the entire lifecycle of an item: from **Lost** to **Found**, and finally to **Claimed**.
- Features secure authentication, image processing, and a robust status tracking ledger.

## Slide 6: System Architecture

- **Strict MVC Compliance**:
    - Controllers handle HTTP routing.
    - Blade handles presentation.
    - Models handle data encapsulation.
- **Service Classes**: Heavy business logic (like handling image uploads and the Smart Match algorithm) is extracted into `ItemService.php` to keep controllers thin and testable.

## Slide 7: Core Features

- Complete CRUD operations for item reporting.
- Secure image uploading and management.
- Interactive dynamic Status Tracking (Lost, Found, Claimed).
- Intelligent Smart Match Recommendation Engine.

## Slide 8: Improvements from Test 1

- **Search & Filtering**: Added dynamic Eloquent queries to filter by keyword, location, and status.
- **Claimed Tracking**: Instead of just deleting items when found, the system now visually archives them with timestamps.
- **Item Details View**: Built a dedicated `show.blade.php` to declutter the main dashboard and show high-resolution details.

## Slide 9: Improvements from Test 2 (Final Exam Adds)

- **Role-Based Security**: Implemented Laravel Gates (`ItemPolicy`). Creators can Edit/Delete; other users can only Claim and View.
- **Interactive Claimant Tracking**: Captures the ID/Name of the person claiming the item via a JS prompt for a secure audit trail.
- **Advanced Algorithm**: Added the Lexical _Smart Match Engine_ that automatically cross-references lost items with found items based on keywords and location.

## Slide 10: OOP Concepts Used

- **Encapsulation**: Used `$fillable` arrays in Models to protect against mass-assignment vulnerabilities.
- **Inheritance**: Models extend Laravel's base `Model`; Controllers extend the base `Controller`.
- **Abstraction**: Business logic is hidden behind simple Service Class method calls (e.g., `$itemService->markItemClaimed()`).

## Slide 11: Challenges & Solutions

- **Challenge**: Corrupt PHP Byte Order Mark (BOM) files causing 500 Server Errors and failing test compilations.
    - **Solution**: Authored custom Python/PowerShell parsing scripts to surgically strip hidden BOM characters from the environment.
- **Challenge**: Users clicking "Claimed" with no audit trail.
    - **Solution**: Integrated a JS prompt appending to hidden form inputs to safely capture claimant data (`claimant_info`).

## Slide 12: System Screenshots (Visual Evidence)

- _(Insert 3-4 screenshots here showcasing: The Dashboard, The Smart Match engine, and the Interactive Claim Prompt)_

## Slide 13: Conclusion

- The Campus Lost & Found tracker has evolved from a basic CRUD application into a secure, robust, industry-ready platform.
- It successfully solves the core problem of campus reunification through automated algorithms and strict data privacy.
- **Thank You!**
