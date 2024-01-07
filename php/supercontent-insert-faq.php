<?php
include("connection.php");

if (isset($_POST['asubmit'])) {
    // Retrieve the submitted question and answer
    $question = mysqli_real_escape_string($con, $_POST['question']);
    $answer = mysqli_real_escape_string($con, $_POST['answer']);

    // Prepare and execute the SQL query
    $sql = "INSERT INTO faq_tbl (question, answer) VALUES ('$question', '$answer')";

    // Execute the query
    if (mysqli_query($con, $sql)) {
        // Redirect the user to the success page
        header("Location: ../admin-user-FAQ.php?success=true");
        exit(); // Make sure to exit after redirecting
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}

?>
