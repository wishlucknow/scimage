<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload Form</title>
  <style>
    body {
      background:rgb(201, 217, 241);
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .form-box {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 5px 25px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }

    .form-box h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .form-box input[type="text"],
    .form-box input[type="file"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      box-sizing: border-box;
      font-size: 16px;
    }

    ::placeholder {
      color: #aaa;
      font-style: italic;
    }

    .form-box button {
      background-color: #4a90e2;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 15px;
      transition: background 0.3s;
    }

    .form-box button:hover {
      background-color: #357ab8;
    }

    label {
      display: block;
      margin: 10px 0 5px;
      color: #555;
      text-align: left;
    }
  </style>
</head>

<body>

  <div class="form-box">
    <h2>Upload Your Data</h2>

    <form action="sv.php" method="post" enctype="multipart/form-data">
      
      <input type="text" name="name" id="name" placeholder="Enter name" required><br>
      
      <input type="text" name="description" id="description" placeholder="Enter description" required><br>

      <label for="img">Choose an image:</label>
      <input type="file" name="image" id="img" accept="image/*" required><br>

      <button type="submit" name="submit">Submit</button>

    </form>
  </div>

</body>
</html>
