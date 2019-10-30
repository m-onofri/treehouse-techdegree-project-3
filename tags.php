<?php 
include('inc/functions.php');

if (isset($_GET['id'])) {
    $tag_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $entries = get_entries_per_tag($tag_id);
}

include('inc/header.php');
?>

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

                    <!-- TODO: enter here list of tags -->
                </article>
            <?php } ?>

        </div>
<?php
include('inc/footer.php');
?>