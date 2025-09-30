# LuxDemo Estate Email Configuration

## Updated .env Configuration

Use these settings in your `.env` file:

```env
# Email Configuration for LuxDemo Estate
MAIL_MAILER=smtp
MAIL_HOST=mail.luxdemoestate.com
MAIL_PORT=465
MAIL_USERNAME=web@luxdemoestate.com
MAIL_PASSWORD="ZPi4Z}rZ704Az&b@"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=web@luxdemoestate.com
MAIL_FROM_NAME="LuxDemo Estate"

# SendGrid API Key (for bulk sending)
SENDGRID_API_KEY=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
```

## SMTP Configuration Details

- **Host:** mail.luxdemoestate.com
- **Port:** 465 (SSL)
- **Username:** web@luxdemoestate.com
- **Password:** ZPi4Z}rZ704Az&b@
- **Encryption:** SSL

## Benefits

✅ **Professional email address** - web@luxdemoestate.com
✅ **Better deliverability** - Using your own domain
✅ **SSL encryption** - Secure email transmission
✅ **No spam issues** - Emails from your own domain

## Testing

After updating .env, test with:
```bash
php artisan tinker
Mail::to('test@example.com')->send(new \App\Mail\SimpleCvMail($cv, 'Test', 'test@example.com'));
```

## DNS Records Still Needed

For SendGrid domain verification, still add these CNAME records:

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

## Hybrid System

- **Single emails:** Use SMTP (mail.luxdemoestate.com)
- **Bulk emails:** Use SendGrid API
- **Automatic selection** based on recipient count
