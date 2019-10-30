<?php 
include('inc/functions.php');

$entries = get_entries();
$tags = get_tags();

include('inc/header.php');
?>

        <div class="entry-list">

            <!-- Display all the entries -->
            <?php foreach ($entries as $entry) { ?>
                <article>
                    <h2>
                        <a href="detail.php?id=<?php echo $entry['id']; ?>">
                            <?php echo $entry['title']; ?>
                        </a>
                    </h2>
                    <time datetime="<?php echo $entry['date']; ?>">
                        <?php echo strftime("%B %e, %G", strtotime($entry['date'])); ?>
                    </time>

                    <!-- TODO: enter here list of tags -->
                </article>
            <?php } ?>

        </div>
<?php
include('inc/footer.php');
?>