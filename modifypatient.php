<!-- This file allows the user to update a patient’s weight in the database. The weight can be entered in either kilograms or pounds, but it is always stored in kilograms.-->
<!-- Database connection closes at the end of the file.-->
<!-- Programmer Name: 72 -->

<?php
include 'connectdb.php';

// Handle weight update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ohip = $_POST['ohip'];
    $weight = $_POST['weight'];
    $unit = $_POST['unit'];

    // Convert weight to kilograms if entered in pounds
    if ($unit == 'lbs') {
        $weight = round($weight / 2.20462,2);
    }

    $update_query = "UPDATE patient SET weight = $weight WHERE ohip = '$ohip'";
    if (mysqli_query($connection, $update_query)) {
        echo "Patient weight updated successfully.";
    } else {
        echo "Error updating patient weight: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modify Patient</title>
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
            margin: auto; /* Center the table */
    	}

    	table td {
            padding: 10px; /* Add spacing between rows */
    	}

    	table td:first-child {
            text-align: right; /* Align labels to the right */
    	}
    </style>
</head>

<body>
    <form action="mainmenu.php" method="get" style="text-align:left;">
        <button type="submit" style="padding: 15px 30px;background-color:#FED8B1;">Back to Main Menu</button>
    </form>
    
    <h1>Modify Patient</h1>
    <form method="post">
	<table>
        <tr>
            <td><label>Enter OHIP:</label></td>
            <td><input type="text" name="ohip" required></td>
        </tr>

        <tr>
            <td><label>New Weight:</label></td>
            <td><input type="number" step="0.1" name="weight" required></td>
            <td>
	    <select name="unit">
            <option value="kg">Kilograms</option>
            <option value="lbs">Pounds</option>
	    </select><br>
	    </td>
        </select><br>
	</tr>

	<tr>
            <td></td>
            <td colspan="2" style="text-align: center; padding-top: 30px;">
                <input type="submit" value="Update Weight" style="padding: 10px 20px; margin-left: -90px;">
            </td>
        </tr>
        </table>
    </form>
</body>
    <!-- Close the database connection -->
    <?php mysqli_close($connection); ?>
</html>
