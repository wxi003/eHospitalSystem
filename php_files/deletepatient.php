<!-- This file facilitates the deletion of a patient from the database either by selecting from a list or entering the OHIP number.-->
<!-- Database connection closes at the end of the file.-->


<?php
include 'connectdb.php';

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_ohip'])) {
    $ohip = $_POST['delete_ohip'];

    // Check if the OHIP exists
    $check_query = "SELECT * FROM patient WHERE ohip = '$ohip'";
    $check_result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Confirm deletion
        if (isset($_POST['confirm'])) {
            $delete_query = "DELETE FROM patient WHERE ohip = '$ohip'";
            if (mysqli_query($connection, $delete_query)) {
                echo "Patient deleted successfully.";
            } else {
                echo "Error deleting patient: " . mysqli_error($connection);
            }
        } else {
            echo "<form method='post'>
                    <input type='hidden' name='delete_ohip' value='$ohip'>
                    Are you sure you want to delete this patient?
                    <input type='submit' name='confirm' value='Yes'>
                    <a href='deletepatient.php'>No</a>
                  </form>";
            exit;
        }
    } else {
        echo "Error: Patient with OHIP $ohip does not exist.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
   <style>
	/* Set the background color */
        body {
            background-color: #fff;
            text-align: center;
        }
	/* Add styling for the form and table */
        form {
            margin-bottom: 20px;
        }

        table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }
    </style>
    
    <title>Delete Patient</title>
</head>

<body>
    <form action="mainmenu.php" method="get" style="text-align:left;">
        <button type="submit" style="padding: 15px 30px;background-color:#FED8B1;">Back to Main Menu</button>
    </form>
   
    <h1>Delete a Patient</h1>
    <form method="post">
        <label>Enter OHIP to delete:</label>
        <input type="text" name="delete_ohip" required>
        <input type="submit" value="Delete Patient">
    </form>
</body>

<!-- Close the database connection -->
    <?php mysqli_close($connection); ?>
</html>
