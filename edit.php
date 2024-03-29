<?php
include('inc/functions.php');

//Set some variables for display the Edit page
$page_title = "Edit";
$page_path = "edit.php";

if (isset($_GET['id'])) {
    //Get and filter the id of the entry to edit
    $entry_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    //Get the data for the selected entry
    $entry = get_entry($entry_id);
    list(, $title, $date, $timeSpent, $learned, $resources) = $entry;
    //Get the tags for the the selected entry and convert the tags list in a string
    $tags = implode(", ", array_map(function($t) { return $t['name'];}, 
                                    get_tags_per_entry($entry_id)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     //Get and filter the data from the form
    $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
    $date = trim(filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING));
    $timeSpent = trim(filter_input(INPUT_POST, 'timeSpent', FILTER_SANITIZE_STRING));
    $learned = trim(filter_input(INPUT_POST, 'whatILearned', FILTER_SANITIZE_STRING));
    $resources = trim(filter_input(INPUT_POST, 'resourcesToRemember', FILTER_SANITIZE_STRING));
    $tags = trim(filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_STRING));
    $entry_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    //Check if all the required fields were filled
    if (empty($title) || empty($date) || empty($timeSpent) || empty($learned)) {
        $error_message = "Please fill in the required fields: Title, Date, 'Time Spent' and 'What I learned'";
    } else {
        //Check if the entry was updated
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

<?php include('inc/footer.php'); ?>