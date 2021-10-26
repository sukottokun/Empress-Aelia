<?php

print("\n==== WP Reset Starting ====\n");
// Get paths for imports
$path  = $_SERVER['DOCUMENT_ROOT'] . '/private/data';

// Import database
echo ('Importing Database...');
$cmd = "wp db import ${path}/database.sql";
passthru($cmd);

// Import media
echo ('Unzipping image files...');
$files = $_SERVER['HOME'] . '/files';
$cmd = "unzip ${path}/uploads.zip -d ${files}";
passthru($cmd);

// Update links
if (!empty($_ENV['PANTHEON_ENVIRONMENT'] && $_ENV['PANTHEON_ENVIRONMENT'] !== 'live') && !empty($_POST['wf_type'] && $_POST['wf_type'] == 'clone_database')) {

    // Get domains
    $old_domain = "https://arcadius-product-uk.lndo.site";
    $new_domain = "https://dev-" . $_SERVER["WPCLI_URL"];

    // Run Search Replace on WPMS sites
    echo ('Beginning Search and Replace...');
    $cmd = "wp search-replace '{$old_domain}' '{$new_domain}' --precise --recurse-objects --all-tables --verbose --skip-columns=guid";
    passthru($cmd);
}

// Regenerate media
passthru('wp media regenerate --yes');

// Get environment variables, create password.
$email = $_POST['user_email'];
//$password = bin2hex(random_bytes(10));
$password = "demo";

// Update WP admin user
$cmd = "wp user update 1 --user_email=${email} --user_pass=${password}";
passthru($cmd);

// Reset admin password
passthru('wp user reset-password 1');

// Clear cache, because why not.
passthru('wp cache flush');

print("\n==== WP Reset Complete ====\n");
