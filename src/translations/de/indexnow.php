<?php

return [
    // Flash messages
    'Settings saved.' => 'Einstellungen gespeichert.',
    'Error saving settings.' => 'Fehler beim Speichern der Einstellungen.',
    'New API key generated and file {key}.txt created in web root.' => 'Neuer API Key generiert und Datei {key}.txt im Web-Root angelegt.',
    'New API key generated: {key} (File could not be created automatically)' => 'Neuer API Key generiert: {key} (Datei konnte nicht automatisch angelegt werden)',
    '{count} URLs successfully submitted to IndexNow.' => '{count} URLs erfolgreich an IndexNow gesendet.',
    'Unknown error' => 'Unbekannter Fehler',
    'Error: {error}' => 'Fehler: {error}',
    'Log cleared.' => 'Log gelöscht.',
    'Queue cleared.' => 'Warteschlange gelöscht.',

    // Service errors
    'API key not configured' => 'API Key nicht konfiguriert',
    'No URLs to submit' => 'Keine URLs zum Senden',
    'No URLs found on site' => 'Keine URLs auf der Seite gefunden',

    // Template: page title & headings
    'IndexNow Settings' => 'IndexNow Einstellungen',
    'General Settings' => 'Allgemeine Einstellungen',
    'Automatic Submission' => 'Automatische Übermittlung',
    'Generate API Key' => 'API Key generieren',
    'Manual Actions' => 'Manuelle Aktionen',
    'URLs in queue' => 'URLs in Warteschlange',
    'Submission Log' => 'Übermittlungs-Log',

    // Template: field labels
    'Enable IndexNow' => 'IndexNow aktivieren',
    'API Key' => 'API Key',
    'IndexNow Endpoint' => 'IndexNow Endpoint',
    'Submit immediately' => 'Sofort senden',
    'Scheduled Submission' => 'Geplante Übermittlung',
    'Cron Job Command' => 'Cron-Job Befehl',

    // Template: field instructions
    'Enables automatic tracking of page changes.' => 'Aktiviert das automatische Tracking von Seiten-Änderungen.',
    'The IndexNow API key. It must also be available as a .txt file in the web root.' => 'Der IndexNow API Key. Dieser muss auch als .txt Datei im Web-Root verfügbar sein.',
    'Select the search engine endpoint for submission.' => 'Wähle den Suchmaschinen-Endpoint für die Übermittlung.',
    'Submits URLs immediately on changes to IndexNow (otherwise they are collected in the queue).' => 'Sendet URLs sofort bei Änderungen an IndexNow (sonst werden sie in der Warteschlange gesammelt).',
    'Time for the daily submission of the queue (if "Submit immediately" is not enabled). Requires a cron job.' => 'Uhrzeit für die tägliche Übermittlung der Warteschlange (falls nicht "Sofort senden" aktiviert ist). Erfordert einen Cron-Job.',
    'Add this command to your cron job for scheduled submissions:' => 'Füge diesen Befehl zu deinem Cron-Job hinzu für geplante Übermittlungen:',

    // Template: placeholders & inline text
    'e.g. a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6' => 'z.B. a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6',
    'Key file {key}.txt was automatically created in the web root.' => 'Key-Datei {key}.txt wurde automatisch im Web-Root angelegt.',
    'IndexNow API (recommended)' => 'IndexNow API (empfohlen)',

    // Template: buttons
    'Save settings' => 'Einstellungen speichern',
    'Generate new key' => 'Neuen Key generieren',
    'Submit queue ({count} URLs)' => 'Warteschlange senden ({count} URLs)',
    'Submit all pages ({count} URLs)' => 'Alle Seiten senden ({count} URLs)',
    'Clear queue' => 'Warteschlange leeren',
    'Clear log' => 'Log leeren',

    // Template: log table headers
    'Timestamp' => 'Zeitpunkt',
    'Status' => 'Status',
    'HTTP Code' => 'HTTP Code',
    'URLs' => 'URLs',
    'Error' => 'Fehler',

    // Template: log status labels
    'Success' => 'Erfolg',

    // Template: empty states
    'No submissions yet.' => 'Noch keine Übermittlungen.',
];
