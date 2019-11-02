<?php
include('inc/functions.php');

if (isset($_GET['id'])) {
    //Get and filter the id of the selected entry
    $entry_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    //Get the selected entry and all the associated tags
    $entry = get_entry($entry_id);
    $tags = get_tags_per_entry($entry_id);
}

if (isset($_POST['delete'])) {
    //Check if the selected entry was deleted
    if (delete_entry(filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_NUMBER_INT))) {
        header('location: index.php');
        die;
    } else {
        header('location: detail.php?msg=Unable+To+Delete+Entry');
        die;
    }
}

include('inc/header.php');
?>
        
<div class="entry-list single">

    <!-- Display all the data for the current entry -->
    <article>
        <h1><?php echo $entry['title']; ?></h1>
        <time datetime="<?php echo $entry['date']; ?>">
            <?php echo strftime("%B %e, %G", strtotime($entry['date'])); ?>
        </time>

        <!-- Display all the tags for the current entry -->
        <?php 
        if (!empty($tags)) {
            echo '<div class="entry">';
            echo "<h3>tags:</h3>";
            echo "<div class='tags-list'>";
            foreach ($tags as $tag) {
                echo "<a href='tags.php?id=" . $tag['id'] . "' class='button button-tag'>" . $tag['name'] . "</a>";
            }
            echo "</div>";
            echo "</div>";
        } 
        ?>

        <div class="entry">
            <h3>Time Spent: </h3>
            <p><?php echo $entry['time_spent']; ?></p>
        </div>
        <div class="entry">
            <h3>What I Learned:</h3>
            <p><?php echo $entry['learned']; ?></p>
        </div>

         <!-- Display all the resources for the current entry -->
        <?php 
        if (!empty($entry['resources'])) {
            $resources = array_map(function($r){return trim($r);}, explode(",", $entry['resources']));
            echo '<div class="entry">';
            echo "<h3>Resources to Remember:</h3>";
            echo "<ul>";
            foreach ($resources as $resource) {
                echo "<li>$resource</li>";
            }
            echo "</ul>";
            echo "</div";
        } 
        ?>
    </article>
</div>

<div class="edit">
    <a class="button" href="edit.php?id=<?php echo $entry['id']; ?>">Edit Entry</a>
    <form method='post' action='detail.php' 
          onsubmit="return confirm('Are you sure you want to delete this entry?')">
        <input type='hidden' value='<?php echo $entry['id']; ?>' name='delete' />
        <input type='submit' class='button button-delete' value='delete' />  
    </form>
</div>
            
<?php include('inc/footer.php'); ?>