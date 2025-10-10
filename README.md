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
git clone https://github.com/tuoaccount/WebAppGAD.git
cd WebAppGAD

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

## Contribuire

Per contribuire al progetto, consulta la [Guida per Sviluppatori](docs/contributing.md).

## Licenza

Questo progetto è rilasciato sotto licenza MIT. Vedi il file [LICENSE](LICENSE) per i dettagli.

## Supporto

Per supporto tecnico o domande:

-   Email: foggetta26@gmail.com
-   Issues: [GitHub Issues](https://github.com/HawkEye26/WebAppGAD/issues)

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com)**
-   **[Tighten Co.](https://tighten.co)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Redberry](https://redberry.international/laravel-development)**
-   **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
