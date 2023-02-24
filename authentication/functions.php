<?php

function connectDb()
{
    try {
        $conn = new PDO("mysql:host=127.0.0.1;dbname=authentication", 'root', '');
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}

function logUser($email, $password)
{
    $connexion = connectDb();
    $sql = $connexion->prepare('SELECT * FROM users WHERE email = ?');

    $sql->bindParam(1, $email);
    $sql->execute();
    $result = $sql->fetch(PDO::FETCH_ASSOC);
    if(!password_verify($password, $result['password'])){
        $result = null;
    }
    return $result;
}

function getUser($id) {
    $connexion = connectDb();
    $sql = $connexion->prepare('SELECT * FROM users WHERE id = ?');
    $sql->bindParam(1,$id);
    $sql->execute();

    return $sql->fetchAll(PDO::FETCH_OBJ);
}

function saveUser($email, $username, $password) {
    $connexion = connectDb();
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = $connexion->prepare('INSERT INTO users(username,email,password) VALUES(?,?,?)');
    $sql->bindParam(1, $email);
    $sql->bindParam(2, $username);
    $sql->bindParam(3, $hashedPassword);

    return $sql->execute();
}