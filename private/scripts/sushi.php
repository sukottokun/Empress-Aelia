<?php

print("\n==== WP Reset Starting ====\n");

// Get paths for imports
$path  = $_SERVER['DOCUMENT_ROOT'] . '/private/data';

// Import database
$cmd = "wp db import ${path}/database.sql";
passthru($cmd);

// Import media
$files = $_SERVER['HOME'] . '/files';
$cmd = "unzip ${path}/uploads.zip -d ${files}";
echo ('Unzipping image files...');
passthru($cmd);

// Update links
$host_url = $_SERVER["HTTP_HOST"];
$new_url = "https://dev-" . $host_url;
$old_url = "http://arcadius-product-uk.lndo.site";
$cmd = "wp search-replace $old_url $new_url --all-tables";
echo ('Unzipping image files...');
passthru($cmd);

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
