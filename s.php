<?php
$id = $name = $desc = $matchedImage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = "uploads/";
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $filename = uniqid("img_") . "_" . basename($_FILES["image"]["name"]);
        $imagePath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
            $pythonPath = "C:/Python312/python.exe";
            $scriptPath = "compare.py";
            $command = escapeshellcmd("$pythonPath $scriptPath " . escapeshellarg($imagePath));
            $output = shell_exec($command);

            file_put_contents("debug.txt", $output); // log output

            $parts = explode('|', trim($output));
            if (count($parts) === 4) {
                list($id, $name, $desc, $matchedImage) = $parts;
            } else {
                $name = "Error: Invalid response from Python script.";
                $matchedImage = "no_match_found.jpg";
            }
        } else {
            $name = "Error uploading file.";
            $matchedImage = "no_match_found.jpg";
        }
    } else {
        $name = "No image received.";
        $matchedImage = "no_match_found.jpg";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Capture & Search Image</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        video, canvas, img { max-width: 300px; margin-top: 10px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <h2>Capture or Upload Image to Search</h2>

    <form id="uploadForm" method="POST" enctype="multipart/form-data">
        <!-- Hidden input to receive captured image for both platforms -->
        <input type="file" name="image" id="mobileInput" accept="image/*" capture="environment" required style="display:none;">

        <div id="desktopCamera">
            <video id="video" autoplay playsinline></video><br>
            <button type="button" onclick="capture()">Capture</button><br>
            <canvas id="canvas" style="display:none;"></canvas>
        </div>

        <input type="submit" value="Search">
    </form>

    <?php if (!empty($name)): ?>
        <h3>Result:</h3>
        <p><strong>ID:</strong> <?= htmlspecialchars($id) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($desc) ?></p>
        <img src="<?= htmlspecialchars($matchedImage) ?>" alt="Matched Image">
    <?php endif; ?>

    <script>
    const isMobile = /iPhone|Android/i.test(navigator.userAgent);
    const mobileInput = document.getElementById('mobileInput');
    const desktopCam = document.getElementById('desktopCamera');

    if (isMobile) {
        mobileInput.style.display = 'block';
        desktopCam.style.display = 'none';
    } else {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => video.srcObject = stream)
            .catch(err => console.error("Camera error:", err));

        window.capture = function () {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0);

            canvas.toBlob(blob => {
                const file = new File([blob], "capture.jpg", { type: "image/jpeg" });
                const dt = new DataTransfer();
                dt.items.add(file);
                mobileInput.files = dt.files;
                document.getElementById('uploadForm').submit();
            }, 'image/jpeg');
        };
    }
    </script>
</body>
</html>
