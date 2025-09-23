-- Database Cleanup Script for CV Maker Application
-- This script removes unused Laravel framework tables that aren't needed for the CV application

-- Drop unused Laravel framework tables
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;

-- Note: Keep the following tables as they are needed:
-- - users (authentication)
-- - user_sessions (session management)
-- - cv (main CV data)
-- - cv_metadata (CV settings)
-- - work_experience (CV work history)
-- - education (CV education)
-- - skills (CV skills)
-- - languages (CV languages)
-- - hobbies (CV hobbies)
-- - audit_logs (custom audit logging)
-- - migrations (Laravel migration tracking)
