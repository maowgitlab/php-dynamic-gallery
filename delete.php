<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['filename'])) {
        $filename = 'img/' . basename($data['filename']);
        if (file_exists($filename)) {
            unlink($filename);
            echo 'Image deleted successfully!';
        } else {
            echo 'Image not found.';
        }
    } else {
        echo 'No filename specified.';
    }
} else {
    echo 'Invalid request method.';
}
