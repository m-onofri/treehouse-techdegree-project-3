<?php

function get_entries() {
    include('connection.php');

    try {
       $results = $db->query('SELECT * FROM entries');
    } catch (Exception $e) {
       $e->getMessage();
    }

    $entries = $results->fetchAll(PDO::FETCH_ASSOC);

    return $entries;
}

function get_entry($entry_id) {
    include('connection.php');

    try {
       $result = $db->prepare('SELECT * FROM entries WHERE id = ?');
       $result->bindValue(1, $entry_id, PDO::PARAM_INT);
       $result->execute();
    } catch (Exception $e) {
       $e->getMessage();
    }

    $entry = $result->fetch(PDO::FETCH_ASSOC);

    return $entry;
}

function add_entry($title, $date, $time_spent, $learned, $resources) {
    include('connection.php');

    try {
       $result = $db->prepare('INSERT INTO entries (title, date, time_spent, learned, resources)
                               VALUES (?, ?, ?, ?, ?)');
       $result->bindValue(1, $title, PDO::PARAM_STR);
       $result->bindValue(2, $date, PDO::PARAM_STR);
       $result->bindValue(3, $time_spent, PDO::PARAM_STR);
       $result->bindValue(4, $learned, PDO::PARAM_STR);
       $result->bindValue(5, $resources, PDO::PARAM_STR);
       if ($result->execute()) {
           return true;
       }
       return false;
    } catch (Exception $e) {
       $e->getMessage();
    }
}
?>