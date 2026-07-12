<?php
function find_files($dir) {
    $results = [];
    if (!is_dir($dir)) return $results;
    $files = scandir($dir);
    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if ($value != "." && $value != "..") {
            if (is_dir($path)) {
                $results = array_merge($results, find_files($path));
            } else {
                if (stripos($value, 'custom_') !== false || stripos($value, 'mens_') !== false || stripos($value, 'uploaded_') !== false) {
                    $results[] = $path;
                }
            }
        }
    }
    return $results;
}

echo "<pre>";
print_r(find_files('c:/xampp/htdocs/ecommerce-main'));
echo "</pre>";
?>
