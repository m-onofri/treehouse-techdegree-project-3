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
?>