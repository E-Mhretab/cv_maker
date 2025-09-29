# E-mail Configuratie voor CV Maker

## Gmail SMTP Configuratie

Om de e-mail functionaliteit te gebruiken, moet je de volgende stappen volgen:

### 1. Gmail App Password aanmaken

1. Ga naar je Google Account instellingen
2. Ga naar "Security" → "2-Step Verification"
3. Zorg dat 2-Step Verification is ingeschakeld
4. Ga naar "App passwords"
5. Maak een nieuwe app password aan voor "Mail"
6. Kopieer het gegenereerde wachtwoord (16 karakters)

### 2. .env Bestand Configureren

Maak een `.env` bestand in de root van je Laravel project met de volgende instellingen:

```env
# Mail Configuration for Gmail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=nathanjethoe007@gmail.com
MAIL_PASSWORD=your-16-character-app-password-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=nathanjethoe007@gmail.com
MAIL_FROM_NAME="CV Maker"
```

### 3. Cache Clearen

Na het configureren van de .env, voer de volgende commando's uit:

```bash
php artisan config:clear
php artisan cache:clear
```

### 4. Test de E-mail Functionaliteit

1. Log in op de website
2. Maak een nieuwe CV aan
3. Controleer of je een e-mail ontvangt met de CV als PDF bijlage
4. Je kunt ook handmatig een e-mail versturen via de "Send Email" knop op de CV pagina's

## Functionaliteiten

### Automatische E-mail Verzending
- Wanneer een ingelogde gebruiker een nieuwe CV aanmaakt, wordt automatisch een e-mail verstuurd
- De e-mail bevat een mooie HTML template met CV details
- De CV wordt als PDF bijlage toegevoegd

### Handmatige E-mail Verzending
- Gebruikers kunnen handmatig een e-mail versturen via:
  - CV Show pagina (groene "Send Email" knop)
  - CV Index pagina (groene envelop icoon)
  - CV Preview pagina (blauwe "Send Email" knop)

### E-mail Template
- Professionele HTML e-mail template
- Bevat CV details en contact informatie
- PDF bijlage met de volledige CV
- Responsive design voor alle e-mail clients

## Troubleshooting

### E-mail wordt niet verstuurd
1. Controleer of de Gmail app password correct is
2. Controleer of 2-Step Verification is ingeschakeld
3. Controleer de Laravel logs: `storage/logs/laravel.log`
4. Test de configuratie met: `php artisan tinker` → `Mail::raw('test', function($msg) { $msg->to('test@example.com'); })`

### PDF Bijlage Problemen
1. Controleer of de PDF service correct werkt
2. Controleer of de storage/app/public map schrijfbaar is
3. Controleer de Laravel logs voor PDF generatie fouten

## Veiligheid

- Gebruik altijd een app password, nooit je normale Gmail wachtwoord
- Bewaar de app password veilig
- Overweeg om een aparte Gmail account te gebruiken voor productie
