<?php

// include("../config/bdd.php");

$conn = mysqli_connect('localhost', 'root', '', 'coffre-fort');

$sql = "SELECT * FROM files";
$result = mysqli_query($conn, $sql);

$files = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (isset($_POST['save'])){
    $filename = $_FILES['myfile']['name'];

    $destination = '../uploads/' . $filename;

    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    $file = $_FILES['myfile']['tmp_name'];
    $size = $_FILES['myfile']['size'];
    if (!in_array($extension, ['zip', 'pdf', 'docx', 'jpg', 'png', 'JPG', 'PNG', 'rar', 'odt', 'doc', 'gif'])) {
        echo "You file extension must be .zip, .pdf, .docx, .jpg, .png, .rar, .odt, .doc or .gif";
        echo "Vous allez être redirigé sur la page d'acceuil.";
            header('Refresh: 5;url=../index.php');
    } elseif ($_FILES['myfile']['size'] > 1000000) { // file shouldn't be larger than 1Megabyte
        echo "File too large!" . "\n";
        echo "Vous allez être redirigé sur la page d'acceuil.";
            header('Refresh: 5;url=../index.php');
    } else {
        // move the uploaded (temporary) file to the specified destination
        if (move_uploaded_file($file, $destination)) {
            $sql = "INSERT INTO files (name, size, downloads) VALUES ('$filename', $size, 0)";
            if (mysqli_query($conn, $sql)) {
                echo "File uploaded successfully" . "\n";
                echo "Vous allez être redirigé sur la page d'acceuil.";
                header('Refresh: 5;url=../index.php');
            }
        } else {
            echo "Failed to upload file." . "\n";
            echo "Vous allez être redirigé sur la page d'acceuil.";
            header('Refresh: 5;url=../index.php');
        }
    }
}
