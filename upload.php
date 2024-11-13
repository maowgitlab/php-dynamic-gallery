<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['file']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);

        if (in_array($filetype, $allowed)) {
            $filepath = 'img/' . $filename;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
                echo 'File uploaded successfully!';
            } else {
                echo 'Error uploading file.';
            }
        } else {
            echo 'Invalid file type.';
        }
    } else {
        echo 'No file uploaded or there was an upload error.';
    }
} else {
    echo 'Invalid request method.';
}
