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
    </style>
</head>

<body class="flex flex-col items-center">
    <h1 class="text-3xl font-bold my-8">Dynamic Image Gallery</h1>
    <div class="gallery flex flex-wrap justify-center">
        <?php
            $dir = 'img/';
            $images = glob($dir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        ?>
        <div class="image m-4 p-2 border border-gray-300 shadow-lg rounded">
            <?php foreach ($images as $image) : ?>
                <img src="<?= $image; ?>" alt="<?= basename($image); ?>" class="max-w-xs cursor-pointer" onclick="showModal('<?= $image; ?>')">
            <?php endforeach; ?>
        </div>
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
    </script>
</body>

</html>