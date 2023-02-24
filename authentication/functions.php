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
    $sql = $connexion->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
    $sql->bindParam(1, $email);
    $sql->bindParam(2, $password);
    $sql->execute();

    return $sql->fetchAll(PDO::FETCH_OBJ);
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
    $sql = $connexion->prepare('INSERT INTO users(username,email,password) VALUES(?,?,?)');
    $sql->bindParam(1, $email);
    $sql->bindParam(2, $username);
    $sql->bindParam(3, $password);

    return $sql->execute();
}