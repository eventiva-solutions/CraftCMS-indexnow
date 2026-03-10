<?php

return [
    // Flash messages
    'Settings saved.' => 'Settings saved.',
    'Error saving settings.' => 'Error saving settings.',
    'New API key generated and file {key}.txt created in web root.' => 'New API key generated and file {key}.txt created in web root.',
    'New API key generated: {key} (File could not be created automatically)' => 'New API key generated: {key} (File could not be created automatically)',
    '{count} URLs successfully submitted to IndexNow.' => '{count} URLs successfully submitted to IndexNow.',
    'Unknown error' => 'Unknown error',
    'Error: {error}' => 'Error: {error}',
    'Log cleared.' => 'Log cleared.',
    'Queue cleared.' => 'Queue cleared.',

    // Service errors
    'API key not configured' => 'API key not configured',
    'No URLs to submit' => 'No URLs to submit',
    'No URLs found on site' => 'No URLs found on site',

    // Template: page title & headings
    'IndexNow Settings' => 'IndexNow Settings',
    'General Settings' => 'General Settings',
    'Automatic Submission' => 'Automatic Submission',
    'Generate API Key' => 'Generate API Key',
    'Manual Actions' => 'Manual Actions',
    'URLs in queue' => 'URLs in queue',
    'Submission Log' => 'Submission Log',

    // Template: field labels
    'Enable IndexNow' => 'Enable IndexNow',
    'API Key' => 'API Key',
    'IndexNow Endpoint' => 'IndexNow Endpoint',
    'Submit immediately' => 'Submit immediately',
    'Scheduled Submission' => 'Scheduled Submission',
    'Cron Job Command' => 'Cron Job Command',

    // Template: field instructions
    'Enables automatic tracking of page changes.' => 'Enables automatic tracking of page changes.',
    'The IndexNow API key. It must also be available as a .txt file in the web root.' => 'The IndexNow API key. It must also be available as a .txt file in the web root.',
    'Select the search engine endpoint for submission.' => 'Select the search engine endpoint for submission.',
    'Submits URLs immediately on changes to IndexNow (otherwise they are collected in the queue).' => 'Submits URLs immediately on changes to IndexNow (otherwise they are collected in the queue).',
    'Time for the daily submission of the queue (if "Submit immediately" is not enabled). Requires a cron job.' => 'Time for the daily submission of the queue (if "Submit immediately" is not enabled). Requires a cron job.',
    'Add this command to your cron job for scheduled submissions:' => 'Add this command to your cron job for scheduled submissions:',

    // Template: placeholders & inline text
    'e.g. a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6' => 'e.g. a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6',
    'Key file {key}.txt was automatically created in the web root.' => 'Key file {key}.txt was automatically created in the web root.',
    'IndexNow API (recommended)' => 'IndexNow API (recommended)',

    // Template: buttons
    'Save settings' => 'Save settings',
    'Generate new key' => 'Generate new key',
    'Submit queue ({count} URLs)' => 'Submit queue ({count} URLs)',
    'Submit all pages ({count} URLs)' => 'Submit all pages ({count} URLs)',
    'Clear queue' => 'Clear queue',
    'Clear log' => 'Clear log',

    // Template: log table headers
    'Timestamp' => 'Timestamp',
    'Status' => 'Status',
    'HTTP Code' => 'HTTP Code',
    'URLs' => 'URLs',
    'Error' => 'Error',

    // Template: log status labels
    'Success' => 'Success',

    // Template: empty states
    'No submissions yet.' => 'No submissions yet.',
];
