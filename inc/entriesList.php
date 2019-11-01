<div class="entry-list">

    <!-- Display all the entries -->
    <?php 
    foreach ($entries as $entry) { 
    //Get all the tags for the current entry
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

            <!-- Display all the tags for te current entry -->
            <?php 
            if (!empty($tags)) {
                echo "<div class='tags-list'>";
                foreach ($tags as $tag) {
                    echo "<a href='tags.php?id=" . $tag['id'] . "' class='button button-tag'>" . $tag['name'] . "</a>";
                }
                echo "</div>";
            } 
            ?>
        </article>
    <?php } ?>

</div>