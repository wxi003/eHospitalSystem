<!-- This file enables the addition of new patients to the database while ensuring data integrity (e.g., unique OHIP numbers)-->
<!-- Database connection closes at the end of the file.-->


<?php
include 'connectdb.php';

$error_message = ""; // Temporary variable for error
$success_message = ""; // Temporary variable for success

// Fetch doctors for dropdown
$doctor_query = "SELECT docid, firstname, lastname FROM doctor";
$doctors = mysqli_query($connection, $doctor_query);

// Validation checks using regular expressions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ohip = trim($_POST['ohip']);
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $birthdate = $_POST['birthdate'];
    $doctor = $_POST['doctor'];

    if (!preg_match('/^[a-zA-Z0-9]{9}$/', $ohip)) {
        $error_message = "Error: OHIP must be exactly 9 characters and contain only letters and digits.";
    } elseif (!preg_match('/^\d+(\.\d{1,2})?$/', $height)) {
        $error_message = "Error: Height must be a float with up to 2 decimal places.";
    } else {

    	// Check OHIP uniqueness
   	 $check_query = "SELECT * FROM patient WHERE ohip = '$ohip'";
    	$check_result = mysqli_query($connection, $check_query);
    	if (mysqli_num_rows($check_result) > 0) {
        	$error_message = "Error: OHIP number already exists.";
   	} else {
        	$insert_query = "INSERT INTO patient (ohip, firstname, lastname, weight, height, birthdate, treatsdocid) 
                         VALUES ('$ohip', '$firstname', '$lastname', $weight, $height, '$birthdate', '$doctor')";
        	if (mysqli_query($connection, $insert_query)) {
            		$success_message = "Patient added successfully.";
        	} else {
            	$error_message = "Error: " . mysqli_error($connection);
        	}
    	}
     }
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Add Patient</title>
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

    	table td:first-child { /*:first-child:Limits the selection to the first <td> in every <tr> (table row)*/
           text-align: right; /* Align labels to the right */
    	}
	</style>
</head>
<body>
     <!-- Display error message -->
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?= $error_message ?></p>
        <?php $error_message = ""; // Clear the error message ?>
    <?php endif; ?>

    <!-- Display success message -->
    <?php if (!empty($success_message)): ?>
        <p style="color: green;"><?= $success_message ?></p>
        <?php $success_message = ""; // Clear the success message ?>
    <?php endif; ?>
	
    <form action="mainmenu.php" method="get" style="text-align:left;">
        <button type="submit" style="padding: 15px 30px;background-color:#FED8B1;">Back to Main Menu</button>
    </form>

    <h1>Add a New Patient</h1>
    
    <form method="post">
    <table>
        <tr>
            <td><label>OHIP:</label></td>
            <td><input type="text" name="ohip" required></td>
        </tr>
        <tr>
            <td><label>First Name:</label></td>
            <td><input type="text" name="firstname" required></td>
        </tr>
        <tr>
            <td><label>Last Name:</label></td>
            <td><input type="text" name="lastname" required></td>
        </tr>
        <tr>
            <td><label>Weight (kg):</label></td>
            <td><input type="number" step="1" min="1" name="weight" required></td>
        </tr>
        <tr>
            <td><label>Height (m):</label></td>
            <td><input type="number" step="0.01" min="1" name="height" required></td>
        </tr>
        <tr>
            <td><label>Birthdate:</label></td>
            <td><input type="date" name="birthdate" required></td>
        </tr>
        <tr>
            <td><label>Assign Doctor:</label></td>
            <td>
                <select name="doctor">
                    <?php
                    while ($row = mysqli_fetch_assoc($doctors)) {
                        echo "<option value='{$row['docid']}'>{$row['firstname']} {$row['lastname']}</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="text-align: center; padding-top: 30px;">
	    	<input type="submit" value="Add Patient" style=" padding: 10px 20px; margin-left: -80px;">
	    </td>
        </tr>
    </table>
    </form>

 
    <!-- Close the database connection -->
    <?php mysqli_close($connection); ?>
</body>
</html>
