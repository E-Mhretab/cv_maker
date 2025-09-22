# CV Database Schema Refactoring

## Overview

This migration refactors the CV database schema to separate personal information from metadata, improving data organization and maintainability.

## Changes Made

### 1. Database Schema Changes

**Before:**
- All CV data (personal info + metadata) stored in single `cv` table
- Columns: `id`, `template_type`, `name`, `address`, `phone_number`, `email`, `date_of_birth`, `linkedin_profile`, `portfolio`, `profile_summary`, `created_at`, `updated_at`, `user_id`, `is_public`, `published_at`

**After:**
- **`cv` table**: Personal information only
  - Columns: `id`, `name`, `address`, `phone_number`, `email`, `date_of_birth`, `linkedin_profile`, `portfolio`, `profile_summary`, `created_at`, `updated_at`, `user_id`
- **`cv_metadata` table**: Template and publication metadata
  - Columns: `id`, `cv_id`, `template_type` (smallint), `is_public`, `published_at`, `created_at`, `updated_at`
  - Foreign key: `cv_id` references `cv.id` with `ON DELETE CASCADE`
  - Template mapping: 1=nathan, 2=esey, 3=mirian
- **`skills` table**: CV skills with descriptions
  - Columns: `id`, `cv_id`, `skill_name`, `description`
  - Foreign key: `cv_id` references `cv.id` with `ON DELETE CASCADE`
- **`languages` table**: Language proficiency levels
  - Columns: `id`, `cv_id`, `language_name`, `proficiency`
  - Foreign key: `cv_id` references `cv.id` with `ON DELETE CASCADE`
  - Proficiency levels: basic, conversational, fluent, native

### 2. Code Changes

All PHP files have been updated to use JOIN queries:

- **`manage/cv_preview.php`**: Updated CV data query with LEFT JOIN
- **`index.php`**: Updated public CVs query with INNER JOIN
- **`user_profile.php`**: Updated user CVs query with LEFT JOIN
- **`manage/cv_list.php`**: Updated CV listing query with LEFT JOIN
- **`create/cv_save.php`**: Split CV creation into two INSERT statements, converts template names to IDs, handles skills and languages
- **`manage/cv_publish.php`**: Updated to modify cv_metadata table
- **`includes/auth.php`**: Updated CV access check with LEFT JOIN
- **`admin_dashboard.php`**: Updated statistics queries with LEFT JOIN
- **`create/cv_create_form.php`**: Added skills and languages form sections
- **`templates/template_nathan.php`**: Added skills and languages display
- **`templates/template_esey.php`**: Added skills and languages display
- **`templates/template_loader.php`**: Updated to pass skills and languages data

## Migration Files

### 1. `migrate_cv_metadata.php`
- **Purpose**: Main migration script
- **What it does**:
  - Creates `cv_metadata` table
  - Migrates existing data from `cv` to `cv_metadata`
  - Verifies migration success
- **Usage**: Run once to migrate your database

### 2. `cleanup_old_cv_columns.php`
- **Purpose**: Remove old columns after successful migration
- **What it does**:
  - Removes `template_type`, `is_public`, `published_at` from `cv` table
  - Removes old indexes
  - **WARNING**: This is irreversible!
- **Usage**: Only run after thorough testing

### 3. `rollback_cv_metadata.php`
- **Purpose**: Revert migration if issues occur
- **What it does**:
  - Adds back old columns to `cv` table
  - Restores data from `cv_metadata` to `cv`
  - Drops `cv_metadata` table
- **Usage**: Run if you need to revert the migration

### 4. `test_schema_migration.php`
- **Purpose**: Test the migration and verify all queries work
- **What it does**:
  - Tests table structure
  - Verifies data migration
  - Tests all JOIN queries used in the application
  - Tests template ID to name conversion
- **Usage**: Run after migration to verify everything works

### 5. `includes/template_utils.php`
- **Purpose**: Helper functions for template type conversion
- **What it does**:
  - Converts template IDs to names and vice versa
  - Provides display names for templates
  - Centralizes template mapping logic
- **Usage**: Included automatically in all files that need template conversion

### 6. `migrate_skills_languages.php`
- **Purpose**: Add skills and languages tables
- **What it does**:
  - Creates `skills` table for CV skills with descriptions
  - Creates `languages` table for language proficiency levels
  - Sets up proper foreign key constraints
- **Usage**: Run after the main CV metadata migration

### 7. `test_skills_languages.php`
- **Purpose**: Test skills and languages functionality
- **What it does**:
  - Tests table creation and structure
  - Tests data insertion and retrieval
  - Tests JOIN queries
  - Cleans up test data
- **Usage**: Run after skills/languages migration to verify functionality

### 8. `rollback_skills_languages.php`
- **Purpose**: Remove skills and languages tables
- **What it does**:
  - Drops foreign key constraints
  - Removes skills and languages tables
  - Permanently deletes all skills and languages data
- **Usage**: Run if you need to rollback the skills/languages changes

### 9. `remove_timestamps_from_skills_languages.php`
- **Purpose**: Remove timestamp columns from existing skills and languages tables
- **What it does**:
  - Removes `created_at` and `updated_at` columns from skills table
  - Removes `created_at` and `updated_at` columns from languages table
  - Simplifies the table structure as requested
- **Usage**: Run if you have existing tables with timestamp columns that need to be removed

### 10. `migrate_data_validation.php`
- **Purpose**: Improve data validation for phone numbers and dates
- **What it does**:
  - Updates `phone_number` column to VARCHAR(20)
  - Checks for existing invalid phone numbers before adding constraints
  - Adds CHECK constraints for phone number format (MySQL 8.0+)
  - Adds CHECK constraints for date ranges in work_experience and education tables
  - Provides fallback validation in application code for older MySQL versions
- **Usage**: Run to add database-level validation constraints

### 10.5. `fix_phone_numbers_before_constraint.php`
- **Purpose**: Clean up existing phone number data before adding constraints
- **What it does**:
  - Identifies phone numbers that don't match the validation pattern
  - Cleans up phone numbers by removing invalid characters
  - Sets invalid phone numbers to NULL if they can't be cleaned
  - Shows detailed report of all changes made
- **Usage**: Run if you get constraint errors due to existing invalid phone numbers

### 11. `test_data_validation.php`
- **Purpose**: Test data validation functionality
- **What it does**:
  - Tests phone number validation with various formats
  - Tests date range validation
  - Tests work experience and education date validation
  - Tests email validation
  - Tests phone number sanitization and formatting
- **Usage**: Run after data validation migration to verify functionality

### 12. `includes/validation.php`
- **Purpose**: Application-level validation functions
- **What it does**:
  - Provides phone number validation with regex pattern
  - Provides date range validation functions
  - Provides email validation
  - Provides phone number sanitization and formatting
  - User-friendly error messages
- **Usage**: Included automatically in forms and processing scripts

## Migration Steps

### Step 1: Backup Your Database
```bash
mysqldump -u username -p database_name > backup_before_migration.sql
```

### Step 2: Run CV Metadata Migration
1. Visit: `http://your-domain.com/E-N/migrate_cv_metadata.php`
2. Follow the on-screen instructions
3. Verify the migration was successful

### Step 3: Run Skills and Languages Migration
1. Visit: `http://your-domain.com/E-N/migrate_skills_languages.php`
2. Follow the on-screen instructions
3. Verify the tables were created successfully

### Step 3.5: Remove Timestamps (if needed)
If you have existing skills and languages tables with timestamp columns:
1. Visit: `http://your-domain.com/E-N/remove_timestamps_from_skills_languages.php`
2. This will remove the created_at and updated_at columns
3. Verify the cleanup was successful

### Step 3.6: Run Data Validation Migration
1. Visit: `http://your-domain.com/E-N/migrate_data_validation.php`
2. If you get an error about invalid phone numbers, run the cleanup script first:
   - Visit: `http://your-domain.com/E-N/fix_phone_numbers_before_constraint.php`
   - This will clean up existing phone number data
   - Then run the migration again
3. Verify the constraints were added successfully

### Step 4: Test the Migrations
1. Visit: `http://your-domain.com/E-N/test_schema_migration.php`
2. Visit: `http://your-domain.com/E-N/test_skills_languages.php`
3. Visit: `http://your-domain.com/E-N/test_data_validation.php`
4. Ensure all tests pass

### Step 5: Test the Application
Test key functionality:
- Create new CV with skills and languages
- Test phone number validation (try invalid formats)
- Test date validation (try end date before start date)
- View existing CVs (should show skills and languages)
- Edit CV
- Publish/unpublish CV
- Export CV

### Step 6: Cleanup (Optional)
**Only run this after thorough testing!**
1. Visit: `http://your-domain.com/E-N/cleanup_old_cv_columns.php`
2. Confirm the cleanup
3. This will permanently remove old columns

## Rollback Instructions

If you encounter issues after migration:

### Rollback Skills and Languages (if needed)
1. Visit: `http://your-domain.com/E-N/rollback_skills_languages.php`
2. Confirm the rollback
3. This will remove skills and languages tables

### Rollback CV Metadata (if needed)
1. Visit: `http://your-domain.com/E-N/rollback_cv_metadata.php`
2. Confirm the rollback
3. This will restore the original schema

## Benefits of This Refactoring

### 1. **Better Data Organization**
- Personal information separated from metadata
- Clearer data relationships
- Easier to maintain and extend

### 2. **Improved Performance**
- Smaller `cv` table for personal data queries
- Indexed metadata table for template/publication queries
- Better query optimization opportunities

### 3. **Enhanced Maintainability**
- Easier to add new metadata fields
- Clear separation of concerns
- Better code organization

### 4. **Future-Proof Design**
- Easy to add new template types
- Simple to extend publication features
- Scalable architecture

## Query Examples

### Before (Old Schema)
```sql
SELECT * FROM cv WHERE is_public = 1 ORDER BY published_at DESC;
```

### After (New Schema)
```sql
SELECT c.*, m.template_type, m.is_public, m.published_at 
FROM cv c 
INNER JOIN cv_metadata m ON c.id = m.cv_id 
WHERE m.is_public = 1 
ORDER BY m.published_at DESC;
```

## Troubleshooting

### Common Issues

1. **"cv_metadata table does not exist"**
   - Solution: Run `migrate_cv_metadata.php` first

2. **"Column 'template_type' doesn't exist"**
   - Solution: The old columns were removed. Use JOIN queries instead.

3. **"No data in cv_metadata"**
   - Solution: Check if migration completed successfully

4. **"Foreign key constraint fails"**
   - Solution: Ensure cv_metadata.cv_id references existing cv.id

### Getting Help

If you encounter issues:
1. Check the test script results
2. Review the error logs
3. Use the rollback script if needed
4. Contact support with specific error messages

## File Structure

```
E-N/
├── migrations/
│   ├── 001_refactor_cv_metadata.sql
│   ├── 002_add_skills_languages.sql
│   └── 003_improve_data_validation.sql
├── migrate_cv_metadata.php
├── migrate_skills_languages.php
├── migrate_data_validation.php
├── fix_phone_numbers_before_constraint.php
├── remove_timestamps_from_skills_languages.php
├── cleanup_old_cv_columns.php
├── rollback_cv_metadata.php
├── rollback_skills_languages.php
├── test_schema_migration.php
├── test_skills_languages.php
├── test_data_validation.php
├── includes/
│   ├── template_utils.php
│   └── validation.php
└── MIGRATION_README.md
```

## Support

This migration has been designed to be:
- **Safe**: Uses transactions and rollback capabilities
- **Tested**: Comprehensive test suite included
- **Reversible**: Full rollback functionality provided
- **Documented**: Clear instructions and examples

For additional support, please refer to the test results and error logs.
