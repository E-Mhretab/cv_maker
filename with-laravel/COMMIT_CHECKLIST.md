# 📋 Pre-Commit Security Checklist

Before pushing to GitHub, always verify:

## ✅ Security Checks

- [ ] `.env` file is **NOT** staged for commit
- [ ] `.env.example` has **placeholder values only** (no real credentials)
- [ ] No API keys in source code files
- [ ] No hardcoded passwords or secrets
- [ ] Upload folders are empty or have only sample files
- [ ] Database files (*.sqlite) are not being committed

## 🔍 Quick Verification Commands

```bash
# Check what files will be committed
git status

# Check staged files in detail
git diff --staged

# Verify .env is ignored
git check-ignore .env

# Search for potential API keys in code
grep -r "API_KEY\|api_key\|SECRET\|password" --include="*.php" app/ | grep -v ".env"

# Check for hardcoded credentials
grep -r "SG\.\|sk_live\|sk_test" --include="*.php" --include="*.js" app/ resources/
```

## 🚨 Files That Should NEVER Be Committed

```
❌ .env
❌ .env.local
❌ .env.production
❌ *.key
❌ *.pem
❌ /storage/app/public/uploads/* (user uploads)
❌ database/database.sqlite (development DB)
```

## ✅ Files That ARE Safe to Commit

```
✅ .env.example (with placeholders)
✅ .gitignore
✅ Source code (*.php, *.js, *.css)
✅ Views and templates
✅ Migration files
✅ Configuration files (without secrets)
✅ Documentation (*.md)
```

## 🔐 Current Protected Information

This project protects:
- **SendGrid API Key** (for email functionality)
- **Database credentials** (username, password)
- **Email credentials** (SMTP username, password)
- **Application encryption key** (APP_KEY)
- **User uploaded files** (profile photos, CV attachments)

## 📝 Safe Commit Process

1. **Review changes:**
   ```bash
   git status
   git diff
   ```

2. **Stage your changes:**
   ```bash
   git add <files>
   ```

3. **Verify no sensitive data:**
   ```bash
   git diff --staged
   ```

4. **Commit:**
   ```bash
   git commit -m "Your descriptive message"
   ```

5. **Push:**
   ```bash
   git push origin branch-name
   ```

## 🆘 If You Accidentally Commit Secrets

1. **DON'T PANIC** - but act quickly
2. **Immediately revoke/rotate** the exposed credential
3. Contact repository admin to force push a cleaned history
4. Review all security documentation

## 📚 Related Documentation

- See `SECURITY.md` for detailed security guidelines
- See `.gitignore` for list of ignored files
- See `.env.example` for environment variable examples

