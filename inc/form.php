
<?php 
if (isset($error_message)) {
    echo "<h2>$error_message</h2>";
} ?>
<h2><?php echo "$page_title Entry"; ?></h2>
<form method="post" action="<?php echo $page_path; ?>">
    <label for="title"> Title</label>
    <input 
        id="title" type="text" name="title" placeholder="New Entry Title"
        value="<?php echo $title; ?>"
    /><br>
    <label for="date">Date</label>
    <input 
        id="date" type="date" name="date" 
        value="<?php echo $date; ?>"    
    /><br>
    <label for="time-spent"> Time Spent</label>
    <input 
        id="time-spent" type="text" name="timeSpent" placeholder="5 hours" 
        value="<?php echo $timeSpent; ?>"
    /><br>
    <label for="what-i-learned">What I Learned</label>
    <textarea id="what-i-learned" rows="5" name="whatILearned" placeholder="Describe here what you learned...">
        <?php echo $learned; ?>
    </textarea>
    <label for="resources-to-remember">Resources to Remember</label>
    <textarea id="resources-to-remember" rows="5" name="resourcesToRemember" placeholder="List here the resources you want to remember...">
        <?php echo $resources; ?>
    </textarea>
    <?php if ($page_title == "Edit") { ?>

        <input type="hidden" name="id" value="<?php echo $entry_id; ?>" />
        <input type="submit" value="Update Entry" class="button">
        <a href="edit.php?id=<?php echo $entry_id; ?>" class="button button-secondary">Cancel</a>

    <?php } elseif ($page_title == "New") { ?>

        <input type="submit" value="Publish Entry" class="button">
        <a href="new.php" class="button button-secondary">Cancel</a>

    <?php } ?>
</form>