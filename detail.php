<?php
include('inc/functions.php');

if (isset($_GET['id'])) {
    $entry_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $entry = get_entry($entry_id);

    // echo '<pre>';
    // echo print_r($entry);
    // echo var_dump($entry['time_spent']);
    // echo '</pre>';
    // die;
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
        <section>
            <div class="container">
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
                        <div class="entry">
                            <h3>Resources to Remember:</h3>
                            <ul>
                                <li><a href="">Lorem ipsum dolor sit amet</a></li>
                                <li><a href="">Cras accumsan cursus ante, non dapibus tempor</a></li>
                                <li>Nunc ut rhoncus felis, vel tincidunt neque</li>
                                <li><a href="">Ipsum dolor sit amet</a></li>
                            </ul>
                        </div>
                    </article>
                </div>
            </div>
            <div class="edit">
                <p>
                    <a href="edit.php?id=<?php echo $entry['id']; ?>">Edit Entry</a>
                </p>
                <form method='post' action='detail.php' onsubmit="return confirm('Are you sure you want to delete this task?')">
                    <input type='hidden' value='<?php echo $entry['id']; ?>' name='delete' />
                    <input type='submit' class='button-delete' value='Delete' />  
                </form>
            </div>
        </section>
<?php 
include('inc/footer.php');
?>