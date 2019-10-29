<?php
include('inc/functions.php');

if (isset($_GET['id'])) {
    $entry_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $entry = get_entry($entry_id);
}

if (isset($_POST['delete'])) {
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
                    <article>
                        <h1><?php echo $entry['title']; ?></h1>
                        <time datetime="<?php echo $entry['date']; ?>">
                            <?php echo strftime("%B %e, %G", strtotime($entry['date'])); ?>
                        </time>
                        <div class="entry">
                            <h3>Time Spent: </h3>
                            <p><?php echo $entry['time_spent']; ?></p>
                        </div>
                        <div class="entry">
                            <h3>What I Learned:</h3>
                            <p><?php echo $entry['learned']; ?></p>
                        </div>

                        <?php if (!empty($entry['resources'])) {
                            $resources = explode(", ", $entry['resources']);
                            echo '<div class="entry">';
                            echo "<h3>Resources to Remember:</h3>";
                            echo "<ul>";
                            foreach ($resources as $resource) {
                                echo "<li>$resource</li>";
                            }
                            echo "</ul>";
                            echo "</div";
                        } ?>

                        <?php if (!empty($entry['tags'])) {
                            $tags = explode(", ", $entry['tags']);
                            echo '<div class="entry">';
                            echo "<h3>tags:</h3>";
                            echo "<ul>";
                            foreach ($tags as $tag) {
                                echo "<li>$tag</li>";
                            }
                            echo "</ul>";
                            echo "</div";
                        } ?>

                    </article>
                </div>
            
                <div class="edit">
                
                    <a class="button" href="edit.php?id=<?php echo $entry['id']; ?>">Edit Entry</a>
                    
                    <form method='post' action='detail.php' onsubmit="return confirm('Are you sure you want to delete this task?')">
                        <input type='hidden' value='<?php echo $entry['id']; ?>' name='delete' />
                        <input type='submit' class='button button-delete' value='delete' />  
                    </form>
                </div>
            
<?php 
include('inc/footer.php');
?>