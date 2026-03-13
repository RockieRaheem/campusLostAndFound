# PROJECT IMPROVEMENT REPORT (TEST 2)

Project Title: Campus Lost & Found Tracker  
Developer: Kamwanga Rahiim  
Registration Number: JAN23/BSE/2177U  
Framework: Laravel (PHP)

## 1. Project Overview

The project is a campus-based Lost and Found web system developed with Laravel. In Test 1, the system supported reporting items and viewing records with basic status handling. For Test 2, I improved the existing project by adding more complete CRUD functionality, cleaner architecture, stronger validation, and better user experience.

## 2. Improvements Made Since Test 1

### New Features Added
- Item edit and update functionality.
- Dedicated item details page.
- Claimed timestamp tracking (`claimed_at`) for better status history.

### Bugs Fixed
- Replaced inline validation with reusable FormRequest classes to reduce rule inconsistencies.
- Improved status transitions to ensure claimed records store claim time correctly.

### Code Improvements
- Introduced `ItemService` to handle business logic outside the controller.
- Refactored controller methods to use service methods and validated request data.

### UI Improvements
- Added action buttons for View, Edit, Claim, and Delete on each item card.
- Improved card layout and action flow for better usability.

### Database Changes
- Added migration: `add_claimed_at_to_items_table`.
- Updated model casting for `claimed_at` as datetime.

## 3. System Enhancements

### Search and Filtering
- Search by item name, description, and location.
- Filter by status: Lost, Found, Claimed.

### Edit and Update Features
- Added routes, controller methods, and a dedicated edit form.
- Users can now modify details and status of existing records.

### Status Tracking
- Added Claimed status support with claim timestamp.
- Claim action now records when an item was claimed.

### Improved Data Validation
- Added `StoreItemRequest` and `UpdateItemRequest` with clear validation rules and messages.

### Better UI/UX
- Added dedicated details page.
- Added clearer actions and status feedback.
- Preserved responsive card-based design from Test 1 improvements.

## 4. Updated Object-Oriented Concepts Used

### Classes and Objects
- `Item` model represents each lost/found record as an object.

### Encapsulation
- `$fillable` and request validation ensure controlled mass assignment and safer data handling.

### Services / Reusable Components
- `ItemService` encapsulates reusable business logic for listing, creating, updating, claiming, and deleting items.

### Inheritance
- Models and requests inherit Laravel base classes (`Model`, `FormRequest`) to reuse framework behavior.

## 5. Challenges and Solutions

### Challenge 1: Keeping controllers clean while adding more features
- Solution: Moved business logic into `ItemService` and kept controllers focused on HTTP flow.

### Challenge 2: Managing different validation rules for create vs update
- Solution: Split validation into `StoreItemRequest` and `UpdateItemRequest`.

### Challenge 3: Tracking real claim history
- Solution: Added `claimed_at` column and automatic timestamp handling during status change.

## 6. Current System Status

### Fully Working
- Create item reports.
- Search and filter items.
- View item details.
- Edit and update items.
- Mark item as claimed.
- Delete items.
- Status statistics dashboard.

### Areas for Further Improvement
- Authentication and role-based access.
- Image upload for item evidence.
- Notifications for potential matches.
- Pagination for large datasets.

## 7. References

- Laravel Documentation: https://laravel.com/docs
- Laravel Validation: https://laravel.com/docs/validation
- Laravel Eloquent ORM: https://laravel.com/docs/eloquent
- Laravel Routing: https://laravel.com/docs/routing
- Laravel Blade: https://laravel.com/docs/blade
- Laracasts: https://laracasts.com
- Stack Overflow: https://stackoverflow.com
