<?php
include('inc/functions.php');
$page_title = "New";
$page_path = "new.php";
$title = $date = $timeSpent = $learned = $resources = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
    $date = trim(filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING));
    $timeSpent = trim(filter_input(INPUT_POST, 'timeSpent', FILTER_SANITIZE_STRING));
    $learned = filter_input(INPUT_POST, 'whatILearned', FILTER_SANITIZE_STRING);
    $resources = filter_input(INPUT_POST, 'resourcesToRemember', FILTER_SANITIZE_STRING);

    // echo '<pre>';
    // echo var_dump($title);
    // echo var_dump($date);
    // echo var_dump($timeSpent);
    // echo var_dump($learned);
    // echo var_dump($resources);
    // echo '</pre>';
    // die;

    if (empty($title) || empty($date) || empty($timeSpent) || empty($learned)) {
        $error_message = "Please fill in the required fields: Title, Date, 'Time Spent' and 'What I learned'";
    } else {
        if (add_entry($title, $date, $timeSpent, $learned, $resources)) {
            header('location: index.php');
        } else {
            $error_message = "Could not add your new entry";
        }
    }
}

include('inc/header.php');
?>
        <section>
            <div class="container">
                <div class="new-entry">
                <?php include('inc/form.php'); ?>
                </div>
            </div>
        </section>
<?php 
include('inc/footer.php');
?>