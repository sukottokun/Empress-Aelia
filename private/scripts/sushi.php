<?php

print("\n==== WP Reset Starting ====\n");

foreach ($_SERVER as $key_name => $key_value) {

    echo $key_name . " = " . $key_value . "\n";
};
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
echo ('Beginning Search and Replace...');
$host_url = $_SERVER["HTTP_HOST"];
echo $host_url;
$new_url = "https://dev-arcadius-product-tokyo.pantheonsite.io";
$old_url = "https://arcadius-product-uk.lndo.site";

if (!empty($_ENV['PANTHEON_ENVIRONMENT'] && $_ENV['PANTHEON_ENVIRONMENT'] !== 'live') && !empty($_POST['wf_type'] && $_POST['wf_type'] == 'clone_database')) {

    // Get environment contexts
    $target = $_POST['to_environment'];
    $source = $_POST['from_environment'];
    echo $target;
    echo $source;

    // Get domains
    $old_domain = "https://arcadius-product-uk.lndo.site";
    $new_domain = "https://dev-" . $host_url;

    // Run Search Replace on WPMS sites
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
