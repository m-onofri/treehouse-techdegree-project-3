<?php

function get_entries() {
    include('connection.php');

    try {
       $results = $db->query('SELECT * FROM entries ORDER BY date DESC');
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

    $entry = $result->fetch();

    return $entry;
}

function get_tags($entry_id = null) {
    include('connection.php');

    try {
        if (!empty($entry_id)) {
            $results = $db->query('SELECT tags.name FROM tags JOIN entries_tags
                                   ON tags.id = entries_tags.tags_id
                                   WHERE entries_tags.entries_id = ?');
            $results->bindValue(1, $entry_id, PDO::PARAM_INT);
            $results->execute();
        } else {
            $results = $db->query('SELECT * FROM tags ORDER BY name');
        }
    } catch (Exception $e) {
       $e->getMessage();
    }

    $tags = $results->fetchAll(PDO::FETCH_ASSOC);

    return $tags;
}

function get_tag_id($tag) {
    include('connection.php');

    try {
        $result = $db->prepare('SELECT id FROM tags WHERE name = ?');
        $result->bindValue(1, $tag, PDO::PARAM_STR);
        $result->execute();
        $tag_id = $result->fetch();
        return $tag_id;
    } catch (Exception $e) {
        $e->getMessage();
    }
}

function add_entry($title, $date, $time_spent, $learned, $resources, $tags, $id = null) {
    include('connection.php');

    try {
        if (!empty($id)) {
            $result = $db->prepare('UPDATE entries 
                                    SET title = ?, date = ?, time_spent = ?, learned = ?, resources = ?
                                    WHERE id = ?');
        } else {
            $result = $db->prepare('INSERT INTO entries (title, date, time_spent, learned, resources)
                                    VALUES (?, ?, ?, ?, ?)');
        }
        $result->bindValue(1, $title, PDO::PARAM_STR);
        $result->bindValue(2, $date, PDO::PARAM_STR);
        $result->bindValue(3, $time_spent, PDO::PARAM_STR);
        $result->bindValue(4, $learned, PDO::PARAM_STR);
        $result->bindValue(5, $resources, PDO::PARAM_STR);
        if (!empty($id)) {
            $result->bindValue(6, $id, PDO::PARAM_INT);
        }
        if ($result->execute()) {
            if (!empty($tags)) {
                if (empty($id)) {
                    $id = $db->lastInsertId();
                }
                add_tags($tags, $id);
            }
            return true;
        }
        return false;
    } catch (Exception $e) {
       $e->getMessage();
    }
}

function add_single_tag($tag) {
    include('connection.php');

    try {
        $result = $db->prepare('INSERT INTO tags (name) VALUES (?)');
        $result->bindValue(1, $tag, PDO::PARAM_STR);
        if ($result->execute()) {
            $tag_id = $db->lastInsertId();
            return $tag_id;
        }  
    } catch (Exception $e) {
        $e->getMessage();
    }
}

function add_tags($tags, $entry_id) {
    include('connection.php');

    $tags_arr = explode(', ', $tags);
    $tags_list = get_tags();
    $entry_tags = get_tags($entry_id);

    foreach ($tags_arr as $tag) {
        if (!in_array($tag, $tags_list)) {
            $tag_id = add_single_tag($tag);
        } else {
           $tag_id = get_tag_id($tag); 
        }
        if (!in_array($tag, $entry_tags)) {
            //execute query to add entry_id and tag_id in entries_tags table
            try {
                $result = $db->prepare('INSERT INTO entries_tags (entries_id, tags_id) VALUES (?, ?)');
                $result->bindValue(1, $entry_id, PDO::PARAM_INT);
                $result->bindValue(2, $tag_id, PDO::PARAM_INT);
                $result->execute();
            } catch (Exception $e) {
                $e->getMessage();
            }
            
        }
    }

    foreach ($entry_tags as $tag) {
        if (!in_array($tag, $tags_arr)) {
            //retrieve the $tag_id
            $tag_id = get_tag_id($tag);
            //in entries_tags table remove all rows with the current $entry_id and $tag_id
            delete_entry_tag($entry_id, $tag_id);
        }
    }

}

function delete_entry_tag($entry_id, $tag_id) {
    include('connection.php');
    try {
        $result = $db->prepare('DELETE FROM entries_tags WHERE entries_id = ? AND tags_id = ?');
        $result->bindValue(1, $entry_id, PDO::PARAM_INT);
        $result->bindValue(2, $tag_id, PDO::PARAM_INT);
        if ($result->execute()) {
            return true;
        }
        
    } catch (Exception $e) {
        $e->getMessage();
    }

    return false;
}

function delete_entry($entry_id) {
    include('connection.php');

    try {
        $result = $db->prepare('DELETE FROM entries WHERE id = ?');
        $result->bindValue(1, $entry_id, PDO::PARAM_INT);

        $result1 = $db->prepare('DELETE FROM entries_tags WHERE entries_id = ?');
        $result1->bindValue(1, $entry_id, PDO::PARAM_INT);

       if ($result->execute() && $result1->execute()) {
            return true;
        }
    } catch (Exception $e) {
       $e->getMessage();
    }

    return false;
}

