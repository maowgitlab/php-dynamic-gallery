<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = ['uploaded' => 0, 'errors' => []];

    if (isset($_FILES['files'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $fileCount = count($_FILES['files']['name']);
        $totalSize = 0;

        for ($i = 0; $i < $fileCount; $i++) {
            $filename = $_FILES['files']['name'][$i];
            $filetype = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $totalSize += $_FILES['files']['size'][$i];

            if (in_array($filetype, $allowed)) {
                $filename = pathinfo($filename, PATHINFO_FILENAME) . '.' . $filetype;
                $filepath = 'img/' . $filename;

                if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $filepath)) {
                    $response['uploaded']++;
                } else {
                    $response['errors'][] = "Error uploading file $filename.";
                }
            } else {
                $response['errors'][] = "Invalid file type for file $filename.";
            }
        }
        $response['totalSize'] = $totalSize;
    } else {
        $response['errors'][] = 'No files uploaded or there was an upload error.';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    echo 'Invalid request method.';
}
