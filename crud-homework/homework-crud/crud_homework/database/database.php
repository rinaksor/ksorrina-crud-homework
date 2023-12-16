<?php

/**
 * Connect to database
 */
if (!function_exists('db')) {
    function db()
    {
        $host     = 'localhost';
        $database = 'web_a';
        $user     = 'root';
        $password = '';

        // Kiểm tra cơ sở dữ liệu đã tồn tại hay chưa
        $databaseExists = (new PDO("mysql:host=localhost", 'root', ''))
            ->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$database'")->fetch();

        // Nếu cơ sở dữ liệu chưa tồn tại, tạo mới
        if (!$databaseExists) {
            (new PDO("mysql:host=$host", $user, $password))->exec("CREATE DATABASE $database");
        }

        // Tạo kết nối PDO
        try {
            $data = new PDO("mysql:host=$host;dbname=$database", $user, $password);
            $data->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $data;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            return null;
        }
    }
}

/**
 * Create new student record
 */
if (!function_exists('createStudent')) {
    function createStudent($values)
    {
        $conn = db();
        $stmt = $conn->prepare("INSERT INTO student (name, age, email, profile) VALUES (?, ?, ?, ?)");
        $stmt->execute([$values['name'], $values['age'], $values['email'], $values['profile']]);
        $conn = null;
    }
}

/**
 * Get all data from table student
 */
if (!function_exists('selectAllStudents')) {
    function selectAllStudents()
    {
        $conn = db();
        $stmt = $conn->query("SELECT * FROM student");
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conn = null;
        return $students;
    }
}

/**
 * Get only one record by id
 */
if (!function_exists('selectOneStudent')) {
    function selectOneStudent($id)
    {
        $conn = db();
        $stmt = $conn->prepare("SELECT * FROM student WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        $conn = null;
        return $student;
    }
}

/**
 * Delete student by id
 */
if (!function_exists('deleteStudent')) {
    function deleteStudent($id)
    {
        $conn = db();
        try {
            $stmt = $conn->prepare("DELETE FROM student WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            echo "Error deleting student: " . $e->getMessage();
        }
        $conn = null;
    }
}

/**
 * Update student
 */
if (!function_exists('updateStudent')) {
    function updateStudent($id, $data)
    {
        $conn = db();

        try {
            $sql = "UPDATE student SET ";
            $placeholders = [];

            foreach ($data as $column => $value) {
                $sql .= "$column = ?, ";
                $placeholders[] = $value;
            }

            $sql = rtrim($sql, ", ") . " WHERE id = ?";
            $placeholders[] = $id;

            $stmt = $conn->prepare($sql);
            $stmt->execute($placeholders);
        } catch (PDOException $e) {
            echo "Error updating student: " . $e->getMessage();
        }
        $conn = null;
    }
}