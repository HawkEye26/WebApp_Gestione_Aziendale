# Manuale d'Uso - WebAppGAD

## Introduzione

WebAppGAD è una piattaforma completa per la gestione aziendale digitale che ti permette di gestire utenti, team e aziende in modo efficiente e sicuro.

## Primi Passi

### Accesso alla Piattaforma

1. **Registrazione**

    - Visita `http://127.0.0.1:8000/register`
    - Compila i campi richiesti: Nome, Email, Password
    - Conferma la registrazione tramite email

2. **Login**
    - Vai su `http://127.0.0.1:8000/login`
    - Inserisci email e password

### Dashboard Principale

Dopo l'accesso, vedrai la dashboard principale con:

-   **Menu superiore**: Navigazione tra le sezioni
-   **Area principale**: Contenuto della sezione selezionata
-   **Header**: Nome sezione e ultimo aggiornamento
-   **Breadcrumb**: Percorso di navigazione

## Gestione Aziende

### Aggiungere Azienda

#### Metodo 1: Inserimento Manuale

1. Vai in "Aziende" → "Aggiungi Azienda"
2. Compila il form:
    - **Ragione sociale**: Nome ufficiale
    - **Indirizzo**: Sede legale completa
    - **CAP**: Codice di avviamento postale
    - **Città**: Nome della città
    - **Provincia**: Provincia di allocazione
    - **Regione**: Regione di allocazione
    - **Contatti**: Indirizzo email
3. Clicca "Salva Azienda"

#### Metodo 2: Import da Excel

1. Vai in "Aziende" → "Import Excel"
2. Carica il file completato
3. Verifica l'anteprima dei dati
4. Clicca "Importa Aziende"

**Formato Excel richiesto**:

-   Colonna A: Ragione Sociale
-   Colonna B: Indirizzo
-   Colonna C: CAP
-   Colonna D: Città
-   Colonna E: Provincia
-   Colonna F: Regione
-   Colonna G: Email

### Gestione Aziende

#### Visualizzazione Lista

-   **Ricerca**: Utilizza la barra di ricerca per trovare aziende
-   **Filtri**: Filtra per settore, dimensione, regione

#### Modifica Azienda

1. Clicca sull'azienda dalla lista
2. Clicca "Modifica"
3. Aggiorna i campi necessari
4. Clicca "Salva Modifiche"

#### Eliminazione Azienda

1. Seleziona l'azienda
2. Clicca sul bottone → "Elimina"
3. Conferma l'eliminazione
4. Utilizzare selezione multipla
5. Clica sul bottonr -> "Elimina Selezionate"

## Funzionalità Avanzate

### Ricerca Globale

Utilizza la barra di ricerca nell'header per cercare:

-   Aziende per ragione sociale
-   Regione
-   Email
-   Ecc...

### Dashboard Personalizzabile

#### Widget Disponibili

-   **Statistiche Aziende**: Contatori e grafici
-   **Team Attivi**: Lista team con membri
-   **Attività Recenti**: Ultime azioni eseguite
-   **Quick Actions**: Scorciatoie alle funzioni più usate

## Risoluzione Problemi Comuni

### Problemi di Accesso

**Password dimenticata**:

1. Clicca "Password dimenticata?" nella pagina di login
2. Inserisci la tua email
3. Controlla la casella email per il link di reset
4. Segui le istruzioni nell'email

**Account bloccato**:

-   Dopo 5 tentativi di login falliti, l'account viene temporaneamente bloccato
-   Attendi 15 minuti o contatta l'amministratore

### Problemi di Performance

**Caricamento lento**:

-   Svuota la cache del browser (Ctrl+F5)
-   Controlla la connessione internet
-   Utilizza i filtri per ridurre i dati visualizzati

**Errori di caricamento**:

-   Ricarica la pagina
-   Disconnettiti e riconnettiti
-   Controlla se ci sono manutenzioni in corso

### Problemi con File

**Upload fallito**:

-   Verifica che il file sia nei formati supportati
-   Controlla che la dimensione non superi i limiti
-   Prova con un browser diverso

## Supporto e Assistenza

### Contatti Support

-   **Email**: 
-   **Telefono**: +39 xxx xxx xxxx
-   **Orari**: Lun-Ven 9:00-18:00

### Risorse Aggiuntive

-   **FAQ**: [Link FAQ]
-   **Video Tutorial**: [Link YouTube]
-   **Forum Community**: [Link Forum]
-   **Documentazione API**: [Link API Docs]

---

_Ultimo aggiornamento: Ottobre 2024_
_Versione Manuale: 1.0_
