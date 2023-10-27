<?php
session_start();
include "koneksi.php";
if (isset($_SESSION['id'])) {
    $id_publisher = $_SESSION['id'];
} else {
    $response['success'] = false;
    $response['message'] = 'You are not logged in';
}

if (isset($_POST["submit"])) {
    $name = $_POST['name'];
    $startprice = $_POST['startprice'];
    $deskripsi = $_POST['deskripsi'];
    $status = 'pending';
    $imgType = $_FILES['foto']['type'];
    $file_content = file_get_contents($_FILES['foto']["tmp_name"]);
    if ($_FILES["foto"]["error"] !== UPLOAD_ERR_OK) {
        $response['success'] = false;
        $response['message'] = 'File upload error: ' . $_FILES["foto"]["error"];
    }
    else{
        $stmt = $conn->prepare("INSERT INTO item (id_publisher, name, startprice, cover, covertype, deskripsi, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $id_publisher, $name, $startprice, $file_content, $imgType, $deskripsi, $status);
        if ($stmt->execute()) {
            echo "Data inserted into the database successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
        $conn->close();
    
        $response['success'] = true;
        $response['message'] = 'Sukses menambahkan item';
    }
   
} else {
    $response['success'] = false;
    $response['message'] = 'Error uploading the file';
}

header('Content-Type: application/json');
echo json_encode($response);
