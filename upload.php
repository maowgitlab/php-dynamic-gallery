<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['files'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $fileCount = count($_FILES['files']['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            $filename = $_FILES['files']['name'][$i];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);

            if (in_array($filetype, $allowed)) {
                $filepath = 'img/' . $filename;

                if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $filepath)) {
                    echo "File $filename uploaded successfully!<br>";
                } else {
                    echo "Error uploading file $filename.<br>";
                }
            } else {
                echo "Invalid file type for file $filename.<br>";
            }
        }
    } else {
        echo 'No files uploaded or there was an upload error.';
    }
} else {
    echo 'Invalid request method.';
}
