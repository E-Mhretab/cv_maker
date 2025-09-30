# LuxDemo Estate Email Setup Guide

## SendGrid Domain Verification for luxdemoestate.com

### 1. DNS Records to Add

Add these CNAME records to your domain DNS settings:

```
Record 1:
Type: CNAME
Name: em6918
Value: u56393383.wl134.sendgrid.net

Record 2:
Type: CNAME  
Name: s1._domainkey
Value: s1.domainkey.u56393383.wl134.sendgrid.net
```

### 2. .env Configuration

Update your `.env` file with these settings:

```env
# Email Configuration for LuxDemo Estate
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=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
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@luxdemoestate.com
MAIL_FROM_NAME="LuxDemo Estate"

# SendGrid API Key
SENDGRID_API_KEY=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
```

### 3. SendGrid Configuration

In your SendGrid dashboard:

1. **Go to Settings** → **Sender Authentication**
2. **Click "Authenticate Your Domain"**
3. **Enter domain:** `luxdemoestate.com`
4. **Add the DNS records** shown above
5. **Verify domain** once DNS propagates

### 4. DNS Propagation

- **Wait 24-48 hours** for DNS records to propagate
- **Check propagation** at: https://dnschecker.org
- **Verify in SendGrid** dashboard

### 5. Benefits of Domain Verification

✅ **Better deliverability** - Emails less likely to go to spam
✅ **Professional sender** - Emails from your own domain
✅ **DKIM authentication** - Prevents email spoofing
✅ **SPF records** - Authorizes SendGrid to send emails
✅ **DMARC policy** - Additional email security

### 6. Testing

After setup, test with:
```bash
php artisan tinker
Mail::to('test@example.com')->send(new \App\Mail\SimpleCvMail($cv, 'Test', 'test@example.com'));
```

### 7. Troubleshooting

**If emails still go to spam:**
1. Add `noreply@luxdemoestate.com` to Gmail contacts
2. Create Gmail filter: From: `noreply@luxdemoestate.com` → Never send to Spam
3. Wait 24-48 hours for reputation to build

**If domain verification fails:**
1. Check DNS records are correct
2. Wait for DNS propagation
3. Verify records in SendGrid dashboard
4. Contact SendGrid support if needed
