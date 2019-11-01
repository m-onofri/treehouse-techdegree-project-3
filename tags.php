<?php 
include('inc/functions.php');

$tags = get_tags();

if (isset($_GET['id'])) {
    $tag_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $tag_name = get_tag($tag_id)['name'];
    $entries = get_entries_per_tag($tag_id);
}

if (isset($_POST['submit']) && isset($_POST['tag'])) {
    $tag_name = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
    $tag_id = get_tag_id($tag_name)['id'];
    $entries = get_entries_per_tag($tag_id);
}

if (isset($_POST['delete']) && isset($_POST['tag'])) {
    $tag_name = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
    $tag_id = get_tag_id($tag_name)['id'];
    if (delete_tag($tag_id)) {
        header('location: tags.php');
        die;
    } else {
        header('location: tags.php?msg=Unable+To+Delete+Entry');
        die;
    }
}

if (isset($_POST['update']) && isset($_POST['tag'])) {
    $tag_name = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
}

if (isset($_POST['change-tag-name']) && isset($_POST['new-name'])) {
    $tag_current_name = filter_input(INPUT_POST, 'current-name', FILTER_SANITIZE_STRING);
    $tag_id = get_tag_id($tag_current_name)['id'];
    $tag_new_name = filter_input(INPUT_POST, 'new-name', FILTER_SANITIZE_STRING);
    if (in_array($tag_new_name, $tags)) {
        header('location: tags.php?msg=The+new+tag+name+already+exist');
    }
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
    <form action="tags.php" method="post">
        <select name="tag">
        <option>Select a tag</option>
        <?php foreach ($tags as $tag) {
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

<?php if (isset($entries)) { ?>

    <div class="entry-list">

        <!-- Display all the entries -->
        <?php foreach ($entries as $entry) { 
            $tags = get_tags_per_entry($entry['id']); 
        ?>
            <article>
                <h2>
                    <a href="detail.php?id=<?php echo $entry['id']; ?>">
                        <?php echo $entry['title']; ?>
                    </a>
                </h2>
                <time datetime="<?php echo $entry['date']; ?>">
                    <?php echo strftime("%B %e, %G", strtotime($entry['date'])); ?>
                </time>
                <?php if (!empty($tags)) {
                    echo "<div class='tags-list'>";
                    foreach ($tags as $tag) {
                        echo "<a href='tags.php?id=" . $tag['id'] . "' class='button button-tag'>" . $tag['name'] . "</a>";
                    }
                    echo "</div>";
                } ?>
            </article>
        <?php } ?>

    </div>
<?php } ?>


<?php include('inc/footer.php'); ?>