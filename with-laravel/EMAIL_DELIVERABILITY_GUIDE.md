# E-mail Deliverability Guide

## Probleem: E-mails komen in spam folder terecht

Dit is een veelvoorkomend probleem met nieuwe e-mail accounts. Hier zijn de oplossingen:

## 1. SPF Record Toevoegen

Voeg een SPF record toe aan je DNS voor `businessdevelopment.es`:

```
TXT record: v=spf1 include:_spf.google.com include:sendgrid.net ~all
```

Of als je alleen je eigen server gebruikt:
```
TXT record: v=spf1 ip4:YOUR_SERVER_IP ~all
```

## 2. DKIM Record Toevoegen

Voeg een DKIM record toe voor betere authenticatie:

```
TXT record: default._domainkey.businessdevelopment.es
Value: v=DKIM1; k=rsa; p=YOUR_DKIM_PUBLIC_KEY
```

## 3. DMARC Policy Toevoegen

Voeg een DMARC record toe:

```
TXT record: _dmarc.businessdevelopment.es
Value: v=DMARC1; p=quarantine; rua=mailto:dmarc@businessdevelopment.es
```

## 4. E-mail Headers Verbeteren

Laten we de e-mail headers verbeteren in de Mailable class.
