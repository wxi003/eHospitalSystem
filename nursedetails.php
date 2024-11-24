<!-- This file displays detailed information about a selected nurse, including the doctors they work for and the total hours worked.-->
<!-- Database connection closes at the end of the file.-->
<!-- Programmer Name: 72 -->

<?php
include 'connectdb.php';

// Get the selected nurse ID
$nurse_id = isset($_POST['nurse_id']) ? $_POST['nurse_id'] : null;

// Fetch all nurses for the dropdown menu
$nurse_query = "SELECT nurseid, firstname, lastname FROM nurse";
$nurses = mysqli_query($connection, $nurse_query);

// Fetch the selected nurse's details
$nurse_details = null;
if ($nurse_id) {
    $nurse_details_query = "SELECT firstname, lastname FROM nurse WHERE nurseid = '$nurse_id'";
    $nurse_details_result = mysqli_query($connection, $nurse_details_query);
    $nurse_details = mysqli_fetch_assoc($nurse_details_result);

    // Fetch the details of the doctors the nurse works for
    $doctor_query = "SELECT doctor.firstname AS doctor_firstname, doctor.lastname AS doctor_lastname, 
                     workingfor.hours 
                     FROM workingfor 
                     JOIN doctor ON workingfor.docid = doctor.docid 
                     WHERE workingfor.nurseid = '$nurse_id'";
    $doctors = mysqli_query($connection, $doctor_query);

    // Fetch the nurse's supervisor details
    $supervisor_query = "SELECT firstname, lastname FROM nurse WHERE nurseid = 
                         (SELECT reporttonurseid FROM nurse WHERE nurseid = '$nurse_id')";
    $supervisor_result = mysqli_query($connection, $supervisor_query);
    $supervisor = mysqli_fetch_assoc($supervisor_result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nurse Details</title>
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
</head>
<body>
    <form action="mainmenu.php" method="get" style="text-align:left;">
        <button type="submit" style="padding: 15px 30px;background-color:#FED8B1;margin-left: 20px;">Back to Main Menu</button>
    </form>
    <h1>Nurse Details</h1>
    <form method="post" style="margin-bottom: 20px;">
        <label>Select Nurse:</label>
        <select name="nurse_id">
            <?php
            while ($row = mysqli_fetch_assoc($nurses)) {
                echo "<option value='{$row['nurseid']}' " . ($nurse_id == $row['nurseid'] ? 'selected' : '') . ">
                        {$row['firstname']} {$row['lastname']}
                      </option>";
            }
            ?>
        </select>
        <input type="submit" value="View Details">
    </form>
    <br> <!-- Add a new line -->
    <?php if ($nurse_id && $nurse_details): ?>
        <h2> <?= $nurse_details['firstname'] ?> <?= $nurse_details['lastname'] ?></h2>
        <table style="border: 1px solid black; border-collapse: collapse;">
            <tr>
                <th>Doctor Name</th>
                <th>Hours Worked</th>
            </tr>
            <?php
            $total_hours = 0;
            while ($row = mysqli_fetch_assoc($doctors)) {
                $total_hours += $row['hours'];
                echo "<tr>
                        <td>{$row['doctor_firstname']} {$row['doctor_lastname']}</td>
                        <td>{$row['hours']}</td>
                      </tr>";
            }
            ?>
        </table>
        <p>Total Hours Worked: <?= $total_hours ?></p>
        <?php if ($supervisor): ?>
            <p>Supervisor: <?= $supervisor['firstname'] ?> <?= $supervisor['lastname'] ?></p>
        <?php else: ?>
            <p>This nurse has no supervisor.</p>
        <?php endif; ?>
    <?php elseif ($nurse_id): ?>
        <p>No details found for the selected nurse.</p>
    <?php endif; ?>

    <!-- Close the database connection -->
    <?php mysqli_close($connection); ?>
</body>
</html>
