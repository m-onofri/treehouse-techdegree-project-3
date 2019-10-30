<?php
include('inc/functions.php');
$page_title = "Edit";
$page_path = "edit.php";

if (isset($_GET['id'])) {
    $entry_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $entry = get_entry($entry_id);
    $tags = implode(", ", get_tags($entry_id));

    list(, $title, $date, $timeSpent, $learned, $resources) = $entry;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
    $date = trim(filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING));
    $timeSpent = trim(filter_input(INPUT_POST, 'timeSpent', FILTER_SANITIZE_STRING));
    $learned = trim(filter_input(INPUT_POST, 'whatILearned', FILTER_SANITIZE_STRING));
    $resources = trim(filter_input(INPUT_POST, 'resourcesToRemember', FILTER_SANITIZE_STRING));
    $tags = trim(filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING));
    $entry_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    
    if (empty($title) || empty($date) || empty($timeSpent) || empty($learned)) {
        $error_message = "Please fill in the required fields: Title, Date, 'Time Spent' and 'What I learned'";
    } else {
        if (add_entry($title, $date, $timeSpent, $learned, $resources, $tags, $entry_id)) {
            header('location: index.php');
        } else {
            $error_message = "Could not update the entry";
        }
    }
}

include('inc/header.php');
?>

        <div class="edit-entry">
            <?php include('inc/form.php'); ?>
        </div>
<?php 
include('inc/footer.php');
?>