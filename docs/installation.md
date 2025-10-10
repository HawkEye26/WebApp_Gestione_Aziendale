# Guida all'Installazione - WebAppGAD

## Requisiti di Sistema

### Requisiti Minimi

-   **PHP**: 8.1 o superiore
-   **Node.js**: 16.x o superiore
-   **npm**: 8.x o superiore
-   **Composer**: 2.x
-   **Database**: SQLite (incluso) o MySQL 8.0+/PostgreSQL 12+

### Estensioni PHP Richieste

-   OpenSSL PHP Extension
-   PDO PHP Extension
-   Mbstring PHP Extension
-   Tokenizer PHP Extension
-   XML PHP Extension
-   Ctype PHP Extension
-   JSON PHP Extension
-   BCMath PHP Extension
-   Fileinfo PHP Extension

## Installazione Passo-Passo

### 1. Download e Preparazione

```bash
# Clona il repository
git clone https://github.com/[TUO-USERNAME]/WebAppGAD.git
cd WebAppGAD

# Oppure scarica lo ZIP e decomprimilo
# wget https://github.com/[TUO-USERNAME]/WebAppGAD/archive/main.zip
# unzip main.zip
# cd WebAppGAD-main
```

### 2. Installazione Dipendenze Backend

```bash
# Installa Composer se non presente
# Download da: https://getcomposer.org/download/

# Installa dipendenze PHP
composer install --no-dev --optimize-autoloader

# Per ambiente di sviluppo
composer install
```

### 3. Installazione Dipendenze Frontend

```bash
# Installa Node.js se non presente
# Download da: https://nodejs.org/

# Installa dipendenze Node.js
npm install

# Verifica l'installazione
npm audit
```

### 4. Configurazione Ambiente

```bash
# Copia il file di configurazione
cp .env.example .env

# Genera la chiave dell'applicazione
php artisan key:generate
```

### 5. Configurazione Database

#### Opzione A: SQLite (Predefinito)

```bash
# Il database SQLite è già configurato
# Verifica che il file database/database.sqlite esista
touch database/database.sqlite
```

#### Opzione B: MySQL

Modifica il file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webappgad
DB_USERNAME=root
DB_PASSWORD=
```

#### Opzione C: PostgreSQL

Modifica il file `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=webappgad
DB_USERNAME=postgres
DB_PASSWORD=
```

### 6. Esecuzione Migrazioni

```bash
# Esegui le migrazioni del database
php artisan migrate

# Popola il database con dati di esempio (opzionale)
php artisan db:seed
```

### 7. Configurazione Storage

```bash
# Crea link simbolici per lo storage
php artisan storage:link

# Imposta i permessi (su Linux/macOS)
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 8. Compilazione Assets

```bash
# Per sviluppo
npm run dev

# Per produzione
npm run build
```

### 9. Avvio dell'Applicazione

```bash
# Avvio server di sviluppo
php artisan serve

# Oppure avvio completo (backend + frontend watch)
npm run dev:all
```

L'applicazione sarà disponibile su: `http://localhost:8000`

## Configurazione Produzione

### Server di Produzione

Per la produzione, utilizza il server PHP integrato su localhost:

```bash
# Avvia il server su localhost
php artisan serve --host=localhost --port=8000
```

L'applicazione sarà disponibile su: `http://localhost:8000`

### Ottimizzazioni Produzione

```bash
# Cache delle configurazioni
php artisan config:cache

# Cache delle route
php artisan route:cache

# Cache delle view
php artisan view:cache

# Ottimizzazione autoloader
composer install --optimize-autoloader --no-dev

# Build assets per produzione
npm run build
```

### Variabili d'Ambiente Produzione

Aggiorna il file `.env` per la produzione:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tuodominio.com

# Configurazioni sicurezza
SANCTUM_STATEFUL_DOMAINS=tuodominio.com
SESSION_DOMAIN=.tuodominio.com
```

## Risoluzione Problemi

### Errore Permessi

```bash
# Linux/macOS
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Windows (eseguire come amministratore)
icacls storage /grant "IIS_IUSRS:(F)" /t
icacls bootstrap\cache /grant "IIS_IUSRS:(F)" /t
```

### Errore Database

```bash
# Verifica connessione
php artisan tinker
DB::connection()->getPdo();

# Reset completo database
php artisan migrate:fresh --seed
```

### Errore Node.js/npm

```bash
# Pulisci cache npm
npm cache clean --force

# Reinstalla node_modules
rm -rf node_modules package-lock.json
npm install
```
