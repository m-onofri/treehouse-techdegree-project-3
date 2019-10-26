<?php 
include('inc/connection.php');
include('inc/functions.php');

$entries = get_entries();

include('inc/header.php');
?>
        <section>
            <div class="container">
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
                        </article>
                    <?php } ?>

                </div>
            </div>
        </section>
<?php
include('inc/footer.php');
?>