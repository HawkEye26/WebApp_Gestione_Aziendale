# Documentazione WebAppGAD

Benvenuto nella documentazione completa di **WebAppGAD** - la piattaforma per la gestione aziendale digitale.

## Indice della Documentazione

### Per Iniziare

-   **[Guida all'Installazione](installation.md)** - Setup completo dell'applicazione
    -   Requisiti di sistema
    -   Installazione passo-passo
    -   Configurazione produzione
    -   Risoluzione problemi

### Per gli Utenti

-   **[Manuale d'Uso](user-guide.md)** - Come utilizzare l'applicazione
    -   Primi passi e registrazione
    -   Gestione profilo e sicurezza
    -   Gestione aziende e import dati
    -   Funzionalità avanzate

### Per gli Sviluppatori

-   **[Documentazione Tecnica](technical-documentation.md)** - Architettura e API

    -   Stack tecnologico
    -   Struttura del progetto
    -   Modelli di dati
    -   API endpoints
    -   Database schema

-   **[Configurazione](configuration.md)** - Tutte le opzioni di setup

    -   File .env e variabili ambiente
    -   Configurazioni database, email, cache
    -   Sicurezza e performance
    -   Ambienti di sviluppo/produzione

-   **[Testing](testing.md)** - Come testare l'applicazione

    -   Setup ambiente di test
    -   Feature tests e unit tests
    -   Test di integrazione
    -   Continuous Integration

-   **[Contributing](contributing.md)** - Guida per contribuire
    -   Setup ambiente di sviluppo
    -   Workflow di contribuzione
    -   Coding standards
    -   Pull request guidelines

## Quick Links

| Cosa Vuoi Fare               | Vai Qui                                       |
| ---------------------------- | --------------------------------------------- |
| **Installare l'app**         | [ Installation Guide](installation.md)        |
| **Imparare ad usarla**       | [ User Guide](user-guide.md)                  |
| **Capire l'architettura**    | [ Technical Docs](technical-documentation.md) |
| **Configurare l'ambiente**   | [ Configuration](configuration.md)            |
| **Sviluppare e contribuire** | [ Contributing](contributing.md)              |
| **Scrivere test**            | [ Testing](testing.md)                        |

## Panoramica del Progetto

### Caratteristiche Principali

-   **Gestione Utenti** con autenticazione sicura (Laravel Jetstream)
-   **Gestione Aziende** con import Excel/CSV
-   **Interface Moderna** con Vue.js 3, Inertia.js e Tailwind CSS
-   **Sistema Ruoli** con Spatie Permission
-   **API Endpoints** per operazioni CRUD

### Stack Tecnologico

```
Frontend: Vue.js 3 + Inertia.js + Tailwind CSS + DaisyUI
Backend:  Laravel 11 + Jetstream + Spatie Permission
Database: SQLite (dev) / MySQL/PostgreSQL (prod)
UI:       Headless UI + Lucide Icons + SweetAlert2
Utils:    Toastify.js + SortableJS + Wind Notify
Build:    Vite + Hot Module Replacement
```

### Struttura Repository

```
├── docs/                    #  Documentazione completa
├── app/                     #  Codice Laravel (Models, Controllers, etc.)
├── resources/js/            #  Frontend Vue.js
├── database/                #  Migrations, Seeds, Factories
├── tests/                   #  Test Suite completa
├── config/                  #  Configurazioni Laravel
└── public/                  #  Assets pubblici
```

## Supporto e Aiuto

### Problemi Comuni

-   **Errori di installazione** → [Installation Guide](installation.md#risoluzione-problemi)
-   **Problemi di autenticazione** → [User Guide](user-guide.md#problemi-di-accesso)
-   **Import aziende** → [User Guide](user-guide.md#gestione-aziende)
-   **Errori di sviluppo** → [Technical Docs](technical-documentation.md)
---

## Versioni e Aggiornamenti

| Versione   | Data           | Highlights            |
| ---------- | -------------- | --------------------- |
| **v1.0.0** | Ottobre 2025   | Prima release stabile |
| **v0.9.0** | Settembre 2025 | Feature complete      |
| **v0.8.0** | Agosto 2025    | Setup iniziale        |

### 🎯 Prossime Versioni

-   **v1.1.0**: Miglioramenti UI/UX e performance
-   **v1.2.0**: Export avanzato e filtri aziende
-   **v1.3.0**: Backup automatico e gestione file
-   **v2.0.0**: Team management (riattivazione Jetstream teams)

---

_Documentazione aggiornata al: Ottobre 2025_  
_Versione documentazione: 1.0_
