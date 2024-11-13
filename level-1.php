<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Dynamic Gallery</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .gallery{
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .gallery .image{
            margin: 10px;
        }
        .gallery .image img{
            max-width: 300px;
            height: auto;
            display: block;
        }
        
    </style>
</head>
<body>
    <h1>Welcome to the PHP Dynamic Gallery</h1>
    <div class="gallery">
        <?php 
            $dir = "img/";
            $images = glob($dir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        ?>
        <?php foreach($images as $image): ?>
            <div class="image">
                <img src="<?= $image; ?>" alt="<?= basename($image); ?>">
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>