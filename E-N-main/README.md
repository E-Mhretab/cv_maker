# CV Management System

A comprehensive web-based CV (Curriculum Vitae) management system built with PHP, MySQL, and Bootstrap.

## Features

- ✅ **CV Creation & Editing** - Create and edit professional CVs
- ✅ **Multiple Templates** - Support for different CV templates (Esey, Nathan)
- ✅ **PDF Export** - Export CVs as PDF using wkhtmltopdf
- ✅ **XML Export** - Export CV data as XML
- ✅ **Responsive Design** - Mobile-friendly interface
- ✅ **Security** - XSS, SQL injection, and CSRF protection
- ✅ **Error Handling** - Comprehensive error management
- ✅ **Performance Optimized** - Optimized database queries

## Installation

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- wkhtmltopdf (for PDF export)

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd E-N
   ```

2. **Database Setup**
   - Create a MySQL database named `cv_system`
   - Import the database schema (if available)
   - Copy `connection.example.php` to `connection.php`
   - Update database credentials in `connection.php`

3. **Configure Web Server**
   - Point your web server to the project directory
   - Ensure PHP extensions are enabled: mysqli, curl, fileinfo

4. **Install wkhtmltopdf** (for PDF export)
   ```bash
   # Ubuntu/Debian
   sudo apt-get install wkhtmltopdf
   
   # macOS
   brew install wkhtmltopdf
   
   # Windows
   # Download from: https://wkhtmltopdf.org/downloads.html
   ```

5. **Set Permissions**
   ```bash
   chmod 755 logs/
   chmod 644 *.php
   ```

## Project Structure

```
E-N/
├── connection.example.php      # Database connection template
├── connection.php             # Database connection (not in git)
├── index.php                  # Main entry point
├── create/                    # CV creation modules
├── manage/                    # CV management modules
├── templates/                 # CV templates
├── css/                       # Stylesheets
├── includes/                  # Shared includes
│   ├── security.php          # Security functions
│   └── error_handler.php     # Error handling
└── .gitignore                # Git ignore rules
```

## Security Features

- **XSS Protection** - Input sanitization and output escaping
- **SQL Injection Prevention** - Prepared statements
- **CSRF Protection** - Token-based protection
- **File Upload Security** - Malware scanning
- **Rate Limiting** - Brute force protection
- **Secure Sessions** - HttpOnly and secure cookies

## Development

### Testing

The system includes comprehensive testing tools:

- **Test Suite** - `test_suite.php` for functionality testing
- **Security Tests** - `security_test.php` for security validation

### Error Handling

- Custom error handlers for development and production
- Detailed error logging
- User-friendly error messages

### Performance

- Optimized database queries
- Query caching
- Performance monitoring

## Configuration

### Database

Update `connection.php` with your database credentials:

```php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "cv_system";
```

### Security

Security settings can be configured in `includes/security.php`:

- Rate limiting thresholds
- File upload restrictions
- Session security settings

## API Endpoints

### CV Management

- `GET /manage/cv_list.php` - List all CVs
- `GET /manage/cv_preview.php?id={id}` - Preview CV
- `GET /manage/cv_edit.php?id={id}` - Edit CV
- `POST /manage/cv_update.php` - Update CV
- `GET /manage/cv_export.php?id={id}&format=pdf` - Export PDF
- `GET /manage/cv_export.php?id={id}&format=xml` - Export XML

### CV Creation

- `GET /create/cv_create_form.php` - Create CV form
- `POST /create/cv_create.php` - Create new CV
- `POST /create/cv_save.php` - Save CV

## Troubleshooting

### Common Issues

1. **PDF Export Not Working**
   - Ensure wkhtmltopdf is installed
   - Check PHP exec functions are enabled
   - Verify file permissions

2. **Database Connection Failed**
   - Check database credentials
   - Ensure MySQL service is running
   - Verify database exists

3. **Permission Errors**
   - Check file permissions
   - Ensure web server has read/write access
   - Verify directory permissions

### Debug Mode

Enable debug mode by setting `DEVELOPMENT = true` in `includes/error_handler.php`

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For support and questions, please contact the development team.

---

**Note**: Never commit sensitive files like `connection.php` or database dumps to version control. Use the provided `.gitignore` file to protect sensitive data.
