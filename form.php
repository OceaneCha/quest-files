<?php
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // form script
    $uploadOK = false;

    $fileDir = 'photos/';

    $fileExtension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $validExtensions = ['jpg', 'png', 'gif', 'webp',];

    $suffix = "." . $fileExtension;
    $uniqueName = uniqid(basename($_FILES['photo']['name'], $suffix), true);
    $uploadPath = $fileDir . $uniqueName . $suffix;

    $maxSize = 1000000;

    if (!in_array($fileExtension, $validExtensions)) {
        $errors[] = "Please use a jpg, png, gif, or webp file.";
    }

    if (file_exists($_FILES['photo']['tmp_name']) && filesize($_FILES['photo']['tmp_name']) > $maxSize) {
        $errors[] = "Please upload a file that weighs less than 1Mo.";
    }

    if (empty($errors) && empty($_FILES['photo']['error'])) {
        move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath);
        if (file_exists($uploadPath)) {
            $uploadOK = true;
        }
    }
}

?>

<!doctype html>

<head>
    <title>My Form</title>
</head>

<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($uploadOK) {
            echo "<div>";
            echo "<p>" . $_POST['firstname'] . " " . $_POST['lastname'] . ", " . $_POST['age'] . " ans.</p>";
            echo '<img src="' . $uploadPath . '" alt="photo" />';
            echo "</div>";
            echo "<div>";
            echo '<a href="form.php?action=delete&name=' . $uploadPath . '">Delete Photo</a>';
            echo "</div>";
        }
    }


    if (isset($_GET['action']) && $_GET['action'] === 'delete') {
        if (isset($_GET['name'])) {
            if (unlink($_GET['name'])) {
                echo "<div><p>File successfully deleted!";
            } else {
                $errors[] = "Couldn't delete file.";
            }
        }
    }

    if (!empty($errors)) {
        var_dump($errors);
    }
    ?>
    <form method="post" enctype="multipart/form-data">
        <div>
            <label for="lastname">Last Name</label>
            <input type="text" name="lastname" placeholder="Last Name">
            <label for="firstname">First Name</label>
            <input type="text" name="firstname" placeholder="Firstname">
        </div>
        <div>
            <label for="age">Age</label>
            <input type="text" name="age" placeholder="Age">
        </div>
        <div>
            <label for="photo">Photo</label>
            <input type="file" name="photo">
        </div>
        <div>
            <button name="Send">Send</button>
        </div>
    </form>
</body>