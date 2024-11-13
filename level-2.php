<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Image Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.7/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.75);
        }

        .upload-container {
            position: relative;
            display: inline-block;
        }

        .file-input {
            display: none;
        }

        .upload-label:hover {
            cursor: pointer;
        }

        .upload-label:hover .file-input {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
        }
    </style>
</head>

<?php
$dir = 'img/';
$images = glob($dir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

$files_with_time = [];
foreach ($images as $image) {
    $files_with_time[$image] = filemtime($image);
}

array_multisort($files_with_time, SORT_DESC, array_keys($files_with_time));
$sorted_images = array_keys($files_with_time);
?>

<body class="flex flex-col items-center">
    <h1 class="text-3xl font-bold my-8">Dynamic Image Gallery</h1>

    <?php if ($sorted_images) : ?>
        <div class="mb-4">
            <button onclick="document.getElementById('fileInput').click()" class="px-4 py-2 bg-blue-500 text-white rounded shadow">Upload New Images</button>
            <input type="file" id="fileInput" class="file-input" accept="image/*" multiple onchange="uploadFiles(this)">
            <button onclick="confirmDeleteAll()" class="px-4 py-2 bg-red-500 text-white rounded shadow ml-2">Delete All Images</button>
        </div>
    <?php endif; ?>
    <div class="gallery flex flex-wrap justify-center">
        <?php if ($sorted_images) : ?>
            <?php foreach ($sorted_images as $image) : ?>
                <div class="image m-4 p-2 border border-gray-300 shadow-lg rounded relative">
                    <div class="flex justify-center items-center">
                        <img src="<?= $image; ?>" alt="<?= basename($image); ?>" class="max-w-xs cursor-pointer" onclick="showModal('<?= $image; ?>')">
                    </div>
                    <p class="mt-2 text-sm text-center font-bold text-gray-600"><?= basename($image); ?></p>
                    <button onclick="deleteImage('<?= basename($image); ?>')" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-lg text-red-600 font-bold border border-gray-300 p-2 rounded-md shadow-sm text-center mt-8">No images found in the directory. You can click <span class="upload-container"><label class="upload-label bg-gray-100 text-2xl text-blue-800 font-mono px-1 py-0.5">HERE<input type="file" class="file-input cursor-pointer" accept="image/*" multiple onchange="uploadFiles(this)"></label></span> to upload images to the folder</p>
        <?php endif; ?>
    </div>

    <!-- Modal for image viewing -->
    <div id="myModal" class="modal">
        <span class="absolute top-4 right-8 text-white text-2xl cursor-pointer" onclick="closeModal()">&times;</span>
        <img class="mx-auto my-10 max-w-full h-auto" id="modalImage">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.7/dist/sweetalert2.min.js"></script>
    <script>
        function showModal(src) {
            var modal = document.getElementById('myModal');
            var modalImg = document.getElementById('modalImage');
            modal.style.display = "block";
            modalImg.src = src;
        }

        function closeModal() {
            var modal = document.getElementById('myModal');
            modal.style.display = "none";
        }

        function uploadFiles(input) {
            var files = input.files;
            var formData = new FormData();
            var totalSize = 0;

            for (var i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
                totalSize += files[i].size;
            }

            Swal.fire({
                title: 'Uploading...',
                text: 'Please wait while your images are being uploaded.',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            fetch('upload.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    if (data.errors.length > 0) {
                        Swal.fire({
                            title: 'Upload Complete with Errors',
                            text: `${data.uploaded} files uploaded successfully! Total size: ${(data.totalSize / (1024 * 1024)).toFixed(2)} MB\nErrors:\n${data.errors.join('\n')}`,
                            icon: 'error'
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            title: 'Upload Complete',
                            text: `${data.uploaded} files uploaded successfully! Total size: ${(data.totalSize / (1024 * 1024)).toFixed(2)} MB`,
                            icon: 'success'
                        }).then(() => location.reload());
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred while uploading your files. Please try again.',
                        icon: 'error'
                    });
                });
        }

        function deleteImage(filename) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while the image is being deleted.',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    fetch('delete.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                filename: filename
                            })
                        })
                        .then(response => response.text())
                        .then(data => {
                            Swal.close();
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Your image has been deleted.',
                                icon: 'success'
                            }).then(() => location.reload());
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error',
                                text: 'An error occurred while deleting your image. Please try again.',
                                icon: 'error'
                            });
                        });
                }
            });
        }

        function confirmDeleteAll() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete all!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while all images are being deleted.',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    fetch('delete_all.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({})
                        })
                        .then(response => response.text())
                        .then(data => {
                            Swal.close();
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'All images have been deleted.',
                                icon: 'success'
                            }).then(() => location.reload());
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error',
                                text: 'An error occurred while deleting all images. Please try again.',
                                icon: 'error'
                            });
                        });
                }
            });
        }
    </script>

</body>

</html>