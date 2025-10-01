# Security Guidelines

## 🔐 Protecting Sensitive Information

This project uses various API keys and credentials that **MUST NEVER** be committed to Git or shared publicly.

## 📋 Environment Variables

All sensitive configuration is stored in the `.env` file, which is **automatically ignored by Git**.

### Setting Up Your Environment

1. **Copy the example file:**
   ```bash
   cp .env.example .env
   ```

2. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

3. **Configure your credentials:**
   Edit `.env` and replace placeholder values with your actual credentials:

   ```env
   # SendGrid API Key (for email functionality)
   SENDGRID_API_KEY=your_actual_sendgrid_api_key

   # Database credentials
   DB_PASSWORD=your_actual_database_password

   # Email credentials
   MAIL_USERNAME=your_actual_email_username
   MAIL_PASSWORD=your_actual_email_password
   ```

## 🚫 What NOT to Commit

**NEVER commit files containing:**
- ❌ `.env` (already in `.gitignore`)
- ❌ API keys (SendGrid, payment gateways, etc.)
- ❌ Database passwords
- ❌ Email credentials
- ❌ Private keys (`.key`, `.pem` files)
- ❌ User uploaded files (`/storage/app/public/uploads/`)

## ✅ What IS Safe to Commit

- ✅ `.env.example` (with placeholder values)
- ✅ Configuration files without credentials
- ✅ Code and application logic
- ✅ Sample/demo images in `/public/img/`

## 🔍 Before Committing

Always check your staged files:

```bash
# Review what you're about to commit
git diff --staged

# Check for accidentally staged sensitive files
git status
```

## 🛡️ Additional Security Measures

1. **Never hardcode credentials** in PHP files
2. **Use environment variables** for all sensitive data
3. **Rotate API keys regularly**
4. **Use different credentials** for development/staging/production
5. **Enable 2FA** on all service accounts (SendGrid, GitHub, etc.)

## 📧 SendGrid API Key

To get a SendGrid API key:
1. Sign up at [SendGrid](https://sendgrid.com/)
2. Navigate to Settings > API Keys
3. Create a new API key with appropriate permissions
4. Copy the key and add to your `.env` file
5. **Store the key securely** - you won't be able to see it again!

## 🆘 If You Accidentally Commit Credentials

If you accidentally commit sensitive information:

1. **Immediately rotate/revoke** the exposed credentials
2. Remove the file from Git history:
   ```bash
   git filter-branch --force --index-filter \
   "git rm --cached --ignore-unmatch PATH_TO_FILE" \
   --prune-empty --tag-name-filter cat -- --all
   ```
3. Force push (⚠️ use with caution):
   ```bash
   git push origin --force --all
   ```
4. Update all team members to pull the new history

## 📚 Resources

- [Laravel Security Best Practices](https://laravel.com/docs/security)
- [OWASP Security Guidelines](https://owasp.org/)
- [GitHub Security Best Practices](https://docs.github.com/en/code-security)

