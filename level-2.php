<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Image Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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

<body class="flex flex-col items-center">
    <h1 class="text-3xl font-bold my-8">Dynamic Image Gallery</h1>
    <div class="gallery flex flex-wrap justify-center">
        <?php
        $dir = 'img/';
        $images = glob($dir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        ?>
        <?php if ($images) : ?>
            <?php foreach ($images as $image) : ?>
                <div class="image m-4 p-2 border border-gray-300 shadow-lg rounded">
                    <div class="flex justify-center items-center">
                        <img src="<?= $image; ?>" alt="<?= basename($image); ?>" class="max-w-xs cursor-pointer" onclick="showModal('<?= $image; ?>')">
                    </div>
                    <p class="mt-2 text-sm text-center font-bold text-gray-600"><?= basename($image); ?></p>
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

            for (var i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }

            fetch('upload.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    alert('Files uploaded successfully!');
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
</body>

</html>