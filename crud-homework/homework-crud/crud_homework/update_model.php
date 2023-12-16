<?php
require "./database/database.php";
if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $age = isset($_POST['age']) ? $_POST['age'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $image_url = isset($_POST['image_url']) ? $_POST['image_url'] : '';

    $student_data = array(
        'name' => $name,
        'age' => $age,
        'email' => $email,
        'profile' => $image_url
    );

    updateStudent($id, $student_data);
    require './index.php';
}
?>