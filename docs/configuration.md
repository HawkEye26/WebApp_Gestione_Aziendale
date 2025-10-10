# 🔧 Guida alla Configurazione - WebAppGAD

## Configurazioni di Base

### File .env

Il file `.env` contiene tutte le configurazioni ambientali dell'applicazione. Copia `.env.example` e personalizza secondo le tue esigenze:

```bash
cp .env.example .env
php artisan key:generate
```

#### Configurazioni Principali

```env
# ==========================================
# CONFIGURAZIONI APPLICAZIONE
# ==========================================

# Nome dell'applicazione (apparirà nei titoli delle pagine)
APP_NAME="WebAppGAD"

# Ambiente: local, staging, production
APP_ENV=local

# Debug: true per sviluppo, false per produzione
APP_DEBUG=true

# URL base dell'applicazione
APP_URL=http://localhost:8000

# Timezone
APP_TIMEZONE=Europe/Rome

# Locale
APP_LOCALE=it
APP_FALLBACK_LOCALE=en

# Chiave di cifratura (generata automaticamente)
APP_KEY=base64:...
```

### Configurazione Database

#### SQLite (Predefinito)

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

#### MySQL

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webappgad
DB_USERNAME=root
DB_PASSWORD=your_password

# Configurazioni aggiuntive MySQL
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
```

#### PostgreSQL

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=webappgad
DB_USERNAME=postgres
DB_PASSWORD=your_password

# Configurazioni aggiuntive PostgreSQL
DB_SCHEMA=public
DB_SSLMODE=prefer
```

### Configurazione Email

#### SMTP Gmail

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@webappgad.com"
MAIL_FROM_NAME="${APP_NAME}"
```

#### SMTP Personalizzato

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-server.com
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

#### Mailgun

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=mg.yourdomain.com
MAILGUN_SECRET=your-mailgun-key
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Configurazione Cache e Sessioni

#### File Cache (Predefinito)

```env
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

#### Redis Cache

```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

#### Database Sessions

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

### Configurazione Queue

#### Sync (Predefinito - Sviluppo)

```env
QUEUE_CONNECTION=sync
```

#### Database Queue

```env
QUEUE_CONNECTION=database
```

#### Redis Queue

```env
QUEUE_CONNECTION=redis
```

### Configurazione Storage

#### Local Storage (Predefinito)

```env
FILESYSTEM_DISK=local
```

#### AWS S3

```env
FILESYSTEM_DISK=s3

AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=eu-west-1
AWS_BUCKET=your-bucket-name
AWS_USE_PATH_STYLE_ENDPOINT=false
```

## Configurazioni Sicurezza

### Sanctum (API Authentication)

```env
# Domini autorizzati per cookie di autenticazione
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1,yourdomain.com

# Dominio per i cookie di sessione
SESSION_DOMAIN=.yourdomain.com

# Cookie sicuri (true per HTTPS)
SESSION_SECURE_COOKIE=false

# Cookie solo HTTP
SESSION_HTTP_ONLY=true

# SameSite cookie policy
SESSION_SAME_SITE=lax
```

### CORS (Cross-Origin Resource Sharing)

Modifica `config/cors.php`:

```php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:3000', 'https://yourdomain.com'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

## Configurazioni Avanzate

### Logging

Modifica `config/logging.php`:

```php
'default' => env('LOG_CHANNEL', 'stack'),

'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single'],
        'ignore_exceptions' => false,
    ],

    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
    ],

    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 14,
    ],
],
```

### Performance

#### Opcache (PHP)

```ini
; php.ini
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
opcache.save_comments=1
```

#### Laravel Optimizations

```env
# Cache delle configurazioni (produzione)
# php artisan config:cache

# Cache delle rotte (produzione)
# php artisan route:cache

# Cache delle view (produzione)
# php artisan view:cache

# Precompilazione classi (produzione)
# composer install --optimize-autoloader --no-dev
```

## Configurazioni Ambiente Specifiche

### Sviluppo (Local)

`.env` per sviluppo:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=log
```

### Test/Staging

`.env` per test:

```env
APP_ENV=staging
APP_DEBUG=false
APP_URL=https://staging.yourdomain.com

# Database separato per test
DB_CONNECTION=mysql
DB_HOST=staging-db-host
DB_DATABASE=webappgad_staging

# Cache con Redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Email con servizio di test
MAIL_MAILER=mailgun
```

### Produzione

`.env` per produzione:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database di produzione
DB_CONNECTION=mysql
DB_HOST=prod-db-host
DB_DATABASE=webappgad_production

# Performance ottimizzate
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Email di produzione
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com

# Storage S3 per file
FILESYSTEM_DISK=s3

# Logging avanzato
LOG_CHANNEL=daily
LOG_LEVEL=warning
```

## Configurazioni Jetstream

### Features Jetstream

Modifica `config/jetstream.php`:

```php
'features' => [
    // Features::termsAndPrivacyPolicy(),
    Features::profilePhotos(),
    // Features::api(),
    Features::teams(['invitations' => true]),
    Features::accountDeletion(),
],

'profile_photo_disk' => 'public',
```

### Team Permissions

```php
// config/jetstream.php
'permissions' => [
    'create',
    'read',
    'update',
    'delete',
],
```

## Configurazioni Fortify

### Features Fortify

Modifica `config/fortify.php`:

```php
'features' => [
    Features::registration(),
    Features::resetPasswords(),
    Features::emailVerification(),
    Features::updateProfileInformation(),
    Features::updatePasswords(),
    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]),
],
```

### Personalizzazione Views

```php
// config/fortify.php
'views' => true,

// Per customizzare le view:
'login' => 'auth.login',
'register' => 'auth.register',
'verify-email' => 'auth.verify-email',
```

## Configurazioni Frontend

### Vite Configuration

Modifica `vite.config.js`:

```javascript
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: "resources/js/app.js",
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            "@": "/resources/js",
        },
    },
    server: {
        host: "0.0.0.0",
        port: 5173,
        hmr: {
            host: "localhost",
        },
    },
});
```

### Tailwind Configuration

Modifica `tailwind.config.js`:

```javascript
const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: "#eff6ff",
                    500: "#3b82f6",
                    900: "#1e3a8a",
                },
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
```

## Backup e Monitoraggio

### Backup Database

Script di backup automatico:

```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/path/to/backups"
DB_NAME="webappgad"

# MySQL Backup
mysqldump -u username -p password $DB_NAME > $BACKUP_DIR/backup_$DATE.sql

# Compress backup
gzip $BACKUP_DIR/backup_$DATE.sql

# Delete backups older than 30 days
find $BACKUP_DIR -name "backup_*.sql.gz" -mtime +30 -delete
```

### Cron Jobs

Aggiungi a crontab:

```bash
# Backup giornaliero alle 2:00
0 2 * * * /path/to/backup.sh

# Queue worker
* * * * * cd /path/to/project && php artisan queue:work --stop-when-empty

# Log cleanup
0 3 * * * cd /path/to/project && php artisan log:clear --days=30
```

### Monitoring

Configurazione per servizi di monitoring:

```env
# Sentry per error tracking
SENTRY_LARAVEL_DSN=https://your-dsn@sentry.io/project-id

# New Relic
NEW_RELIC_LICENSE_KEY=your-license-key
NEW_RELIC_APPNAME="WebAppGAD"
```

---

_Ultimo aggiornamento: Ottobre 2024_
_Versione: 1.0_
