# WebAppGAD - Gestione Aziendale Digitale

![Laravel](https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel)
![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green?style=for-the-badge&logo=vue.js)
![Status](https://img.shields.io/badge/Status-In%20Development-yellow?style=for-the-badge)

## Descrizione del Progetto

WebAppGAD è una moderna applicazione web per la gestione aziendale digitale, sviluppata con Laravel e Vue.js. L'applicazione offre strumenti completi per la gestione di aziende, team e utenti attraverso un'interfaccia intuitiva e reattiva.

### Caratteristiche Principali

-   **Gestione Utenti**: Sistema completo di autenticazione e autorizzazione
-   **Gestione Team**: Creazione e amministrazione di team aziendali
-   **Gestione Aziende**: Funzionalità avanzate per la gestione delle informazioni aziendali
-   **Import/Export**: Importazione massiva di dati aziendali tramite Excel, Json o CSV
-   **Interfaccia Moderna**: UI/UX moderna con Vue.js 3 e Tailwind CSS
-   **Sicurezza**: Autenticazione a due fattori e gestione sicura delle sessioni

### Stack Tecnologico

-   **Backend**: Laravel 11.x con PHP 8.x
-   **Frontend**: Vue.js 3 con Composition API
-   **Styling**: Tailwind CSS + Naive UI
-   **Database**: SQLite (configurabile per MySQL/PostgreSQL)
-   **Autenticazione**: Laravel Fortify + Jetstream
-   **Build Tool**: Vite
-   **Testing**: PHPUnit + Feature Tests

## Quick Start

```bash
# Clona il repository
git clone https://github.com/HawkEye26/WebApp_Gestione_Aziendale
cd WebApp_Gestione_Aziendale

# Installa dipendenze PHP
composer install

# Installa dipendenze Node.js
npm install

# Configura l'ambiente
cp .env.example .env
php artisan key:generate

# Esegui le migrazioni
php artisan migrate

# Avvia il server di sviluppo
npm run dev:all
```

## Documentazione

-   [Guida all'Installazione](docs/installation.md) - Istruzioni complete per installare e configurare l'applicazione
-   [Manuale d'Uso](docs/user-guide.md) - Guida completa per utilizzare tutte le funzionalità
-   [Documentazione Tecnica](docs/technical-documentation.md) - Architettura, API e dettagli tecnici
-   [Configurazione](docs/configuration.md) - Tutte le opzioni di configurazione disponibili
-   [Testing](docs/testing.md) - Come eseguire e scrivere test per l'applicazione
-   [Contributing](docs/contributing.md) - Guida per sviluppatori che vogliono contribuire

## Licenza

Questo progetto è rilasciato sotto licenza MIT. Vedi il file [LICENSE](LICENSE) per i dettagli.

## Supporto

Per supporto tecnico o domande:

-   Email: foggetta26@gmail.com
-   Issues: [GitHub Issues](https://github.com/HawkEye26/WebApp_Gestione_Aziendale/issues)
