<?php
/* Get all the entries
** No parameters
** Returns an array of entries as associated arrays */
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

/* Get all the entries for a specific tags
** Parameter: id of the selected tag
** Returns an array of entries as associated arrays */
function get_entries_per_tag($tag_id) {
    include('connection.php');

    try {
        $results = $db->query('SELECT entries.* FROM entries JOIN entries_tags
                                ON entries.id = entries_tags.entries_id
                                WHERE entries_tags.tags_id = ?
                                ORDER BY entries.date DESC');
        $results->bindValue(1, $tag_id, PDO::PARAM_INT);
        $results->execute();
    } catch (Exception $e) {
       $e->getMessage();
    }

    $entries = $results->fetchAll(PDO::FETCH_ASSOC);

    return $entries;
}

/* Get a specific entry
** Parameter: id of the selected entry
** Returns the entry as an associated array */
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

/* Get the tas for a specific entry
** Parameter: id of the selected entry
** Returns an array of tags as associated arrays */
function get_tags_per_entry($entry_id) {
    include('connection.php');

    try {
        $results = $db->query('SELECT tags.name, tags.id FROM tags JOIN entries_tags
                                ON tags.id = entries_tags.tags_id
                                WHERE entries_tags.entries_id = ?');
        $results->bindValue(1, $entry_id, PDO::PARAM_INT);
        $results->execute();
    } catch (Exception $e) {
       $e->getMessage();
    }

    $tags = $results->fetchAll(PDO::FETCH_ASSOC);

    return $tags;
}

/* Get all the tags
** No parameters
** Returns an array of the tags' name */
function get_tags() {
    include('connection.php');

    try {
        $results = $db->query('SELECT name FROM tags ORDER BY name');
    } catch (Exception $e) {
       $e->getMessage();
    }

    $tags = array_map(function($t) { return $t['name'];}, $results->fetchAll(PDO::FETCH_ASSOC));

    return $tags;
}

/* Get a specific tag
** Parameter: id of the selected tag
** Returns the tag as an associated array */
function get_tag($tag_id) {
    include('connection.php');

    try {
        $result = $db->prepare('SELECT name FROM tags WHERE id = ?');
        $result->bindValue(1, $tag_id, PDO::PARAM_INT);
        $result->execute();
    } catch (Exception $e) {
        $e->getMessage();
    }

    $tag_name = $result->fetch(PDO::FETCH_ASSOC);

    return $tag_name;
}

/* Get a specific tag id
** Parameter: name of the selected tag
** Returns the tag id as an associated array */
function get_tag_id($tag) {
    include('connection.php');

    try {
        $result = $db->prepare('SELECT id FROM tags WHERE name = ?');
        $result->bindValue(1, $tag, PDO::PARAM_STR);
        $result->execute();
    } catch (Exception $e) {
        $e->getMessage();
    }

    $tag_id = $result->fetch();

    return $tag_id;
}

/* Create or update an entry
** Parameter: title, date, time spent, what learned, resources, tag, id (optional)
** Returns TRUE if the entry was created or updated, otherweise returns FALSE */
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

/* Create or update a tag
** Parameter: tag name, id (optional)
** Returns the tag id if the tag was created and TRUE if the tag was updated, otherweise returns FALSE */
function add_single_tag($tag, $id = null) {
    include('connection.php');

    try {
        if (!empty($id)) {
            $result = $db->prepare('UPDATE tags SET name = ? WHERE id = ?');
        } else {
            $result = $db->prepare('INSERT INTO tags (name) VALUES (?)');
        }
        $result->bindValue(1, $tag, PDO::PARAM_STR);
        if (!empty($id)) {
            $result->bindValue(2, $id, PDO::PARAM_INT);
        }
        if ($result->execute()) {
            if (!empty($id)) {
                return true;
            } else {
                $tag_id = $db->lastInsertId();
                return $tag_id;
            }
        }  
    } catch (Exception $e) {
        $e->getMessage();
    }
    return false;
}

/* Add a list of tags for a specific entry in the database
** Parameter: tag list as an array, entry id
** Returns TRUE if the tags was correctly added to the database, otherweise returns FALSE */
function add_tags($tags, $entry_id) {
    include('connection.php');

    $tags_arr = array_map(function($t) {return trim($t);}, explode(',', $tags));
    //Get all the tags in the tags table
    $tags_list = get_tags();
    //Get all the tags associated with the entry
    $entry_tags = array_map(function($t) { return $t['name'];}, get_tags_per_entry($entry_id));

    foreach ($tags_arr as $tag) {
        //Check if $tag is already in the tags table
        if (!in_array($tag, $tags_list)) {
            //if not, add the tag to the tags table
            $tag_id = add_single_tag($tag);
        } else {
            //otherwise get the id of the tag
           $tag_id = get_tag_id($tag)['id']; 
        }

        //Check if $tag is already associated to the entry
        if (!in_array($tag, $entry_tags)) {
            //if not, add the entry id and the tag id to the enries_tags table
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

    foreach ($entry_tags as $tag1) {
        //Check if the user removes a tag for the selected entry
        if (!in_array($tag1, $tags_arr)) {
            //if so get the tag id
            $tag_id = get_tag_id($tag1)['id'];
            //and remove all rows with the current $entry_id and $tag_id
            $result1 = delete_entry_tag($entry_id, $tag_id);
        }
    }

    return true;
}

/* Remove a tag from the tags' list of a specific entry
** Parameter: entry id, tag id
** Returns TRUE if the tag was correctly removed from the tags' list of the entry, otherweise returns FALSE */
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

/* Delete a specific entry
** Parameter: entry id
** Returns TRUE if the entry was correctly removed from the entries table and entries_tags table, otherweise returns FALSE */
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

/* Delete a specific tag
** Parameter: tag id
** Returns TRUE if the tag was correctly removed from the tags table and entries_tags table, otherweise returns FALSE */
function delete_tag($tag_id) {
    include('connection.php');

    try {
        $result = $db->prepare('DELETE FROM tags WHERE id = ?');
        $result->bindValue(1, $tag_id, PDO::PARAM_INT);

        $result1 = $db->prepare('DELETE FROM entries_tags WHERE tags_id = ?');
        $result1->bindValue(1, $tag_id, PDO::PARAM_INT);

       if ($result->execute() && $result1->execute()) {
            return true;
        }
    } catch (Exception $e) {
       $e->getMessage();
    }

    return false;
}