<?php 
include('inc/functions.php');

//Get all the tags
$tags = get_tags();

//Get all the data to display all the entry with the tag selected from the index page or the detail page
if (isset($_GET['id'])) {
    //Get and filter the id of the selected tag, then get the tag name and the list of entries with the selected tag
    $tag_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $tag_name = get_tag($tag_id)['name'];
    $entries = get_entries_per_tag($tag_id);
}

//Get all the data to display all the entry with the tag selected from the form in the tags page
if (isset($_POST['submit']) && isset($_POST['tag'])) {
    //Get and filter the name of the selected tag, then get the tag id and the list of entries with the selected tag
    $tag_name = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
    $tag_id = get_tag_id($tag_name)['id'];
    $entries = get_entries_per_tag($tag_id);
}

//Check if the delete button in the form was clicked
if (isset($_POST['delete']) && isset($_POST['tag'])) {
    //Get and filter the name of the tag you want delete, then get the tag id
    $tag_name = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
    $tag_id = get_tag_id($tag_name)['id'];
    //Check if the tag was deleted
    if (delete_tag($tag_id)) {
        header('location: tags.php');
        die;
    } else {
        header('location: tags.php?msg=Unable+To+Delete+Entry');
        die;
    }
}

//Check if the update button in the form was clicked
if (isset($_POST['update']) && isset($_POST['tag'])) {
    //Get and filter the name of the tag you want to update
    $tag_name = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
}

//Check if a new tag name is submitted
if (isset($_POST['change-tag-name']) && isset($_POST['new-name'])) {
    //Get and filter the name of the tag you want update, then get the tag id
    $tag_current_name = filter_input(INPUT_POST, 'current-name', FILTER_SANITIZE_STRING);
    $tag_id = get_tag_id($tag_current_name)['id'];
    //Get and filter the new name of the tag
    $tag_new_name = filter_input(INPUT_POST, 'new-name', FILTER_SANITIZE_STRING);
    //Check if the new tag name already exist in the tags table
    if (in_array($tag_new_name, $tags)) {
        header('location: tags.php?msg=The+new+tag+name+already+exist');
    }
    //Check if the tag is updated
    if (add_single_tag($tag_new_name, $tag_id)) {
        header('location: tags.php');
        die;
    } else {
        header('location: tags.php?msg=Unable+To+Update+Tag+Name');
        die;
    }
}

include('inc/header.php');
?>

<div class="tags-control">
    <!-- Display the form to select a tag and the buttons to update or delete the selected tag -->
    <form action="tags.php" method="post">
        <select name="tag">
        <option>Select a tag</option>
        <?php 
        foreach ($tags as $tag) {
            if (isset($tag_id) && ($tag == $tag_name)) {
                echo "<option value='$tag' selected>$tag</option>";
            } else {
                echo "<option value='$tag'>$tag</option>";
            }
        }
        ?>
        </select>
        <input class="button" type="submit" name="submit" value="List Entries" />
        <input class="button button-update" type="submit" name="update" value="Update" />
        <input class="button button-delete" type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete the selected tag?')" />
    </form>
</div>

<!-- Display this form when update button was selected -->
<?php if (isset($_POST['update'])) { ?>
    <div class="edit-tag">
        <form action="tags.php" method="post">
            <label for="current-name">Current tag name</label>
            <input type="text" id="current-name" name="current-name" value="<?php echo $tag_name; ?>" />
            <label for="current-name">New tag name</label>
            <input type="text" id="new-name" name="new-name">
            <input type="submit" class="button" value="update" name="change-tag-name">
        </form>
    </div>
<?php } ?>

<!-- Display list of entries with the selected tag  -->
<?php 
if (isset($entries)) { 
    include('inc/entriesList.php');
}
?>
    
<?php include('inc/footer.php'); ?>