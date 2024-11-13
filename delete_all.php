<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dir = 'img/';
    $images = glob($dir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

    foreach ($images as $image) {
        if (file_exists($image)) {
            unlink($image);
        }
    }
    echo 'All images have been deleted successfully!';
} else {
    echo 'Invalid request method.';
}
