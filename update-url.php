<?php
$dbPath = __DIR__ . '/writable/database/scripts.db';
try {
    $db = new SQLite3($dbPath);
    $db->enableExceptions(true);

    // Replace .ps1 | pwsh to .sh | bash
    $db->exec("UPDATE scripts SET command_linux = REPLACE(command_linux, '.ps1 | pwsh', '.sh | bash')");
    $db->exec("UPDATE scripts SET command_linux = REPLACE(command_linux, '.ps1 | pwsh', '.sh | bash')"); // Just in case
    
    echo "Database command_linux updated successfully.\n";
} catch (Exception $e) {
    echo "Error updating database: " . $e->getMessage() . "\n";
}
