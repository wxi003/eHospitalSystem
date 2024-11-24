<!-- This file displays a list of all patients from the database with options to sort by first name or last name and to choose ascending or descending order.-->
<!-- Database connection closes at the end of the file.-->
<!-- Programmer Name: 72 -->
<?php
include 'connectdb.php';

// Get sorting options from user input
$sort_field = isset($_POST['sort_field']) ? $_POST['sort_field'] : 'lastname';
$sort_order = isset($_POST['sort_order']) ? $_POST['sort_order'] : 'ASC';

// Query to fetch patients and their doctors
$query = "SELECT patient.ohip, patient.firstname, patient.lastname, patient.weight, patient.height, 
          doctor.firstname AS doctor_firstname, doctor.lastname AS doctor_lastname 
          FROM patient 
          LEFT JOIN doctor ON patient.treatsdocid = doctor.docid 
          ORDER BY $sort_field $sort_order";
$result = mysqli_query($connection, $query);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient List</title>
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
        <button type="submit" style="padding: 15px 30px;background-color:#FED8B1;">Back to Main Menu</button>
    </form>
    <h1>Patient List</h1>
    <form method="post">
        <label><b>Sort By:</b></label>
        <input type="radio" name="sort_field" value="firstname" <?php if ($sort_field == 'firstname') echo 'checked'; ?>> First Name
        <input type="radio" name="sort_field" value="lastname" <?php if ($sort_field == 'lastname') echo 'checked'; ?>> Last Name
	
	&nbsp; <!-- Add extra space -->
	&nbsp; <!-- Add extra space -->
	&nbsp; <!-- Add extra space -->        
	
	<label><b>Order:</b></label>
        <input type="radio" name="sort_order" value="ASC" <?php if ($sort_order == 'ASC') echo 'checked'; ?>> Ascending
        <input type="radio" name="sort_order" value="DESC" <?php if ($sort_order == 'DESC') echo 'checked'; ?>> Descending
        <input type="submit" value="Sort">
    </form>
    <table style="border: 1px solid black; border-collapse: collapse;">
        <tr>
            <th>OHIP</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Weight (lbs)</th>
            <th>Height (ft-in)</th>
            <th>Doctor</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $weight_kg = $row['weight'];
            $weight_lbs = round($weight_kg * 2.20462, 2);

            $height_m = $row['height'];
	    $height_cm = $height_m * 100;

            $height_ft = floor($height_cm / 30.48);
	    $height_ft_remainder = round($height_cm / 30.48,2) - floor($height_cm / 30.48);
            $height_in = round($height_ft_remainder * 12,1);
            echo "<tr>
                    <td>{$row['ohip']}</td>
                    <td>{$row['firstname']}</td>
                    <td>{$row['lastname']}</td>
                    <td>{$weight_lbs} </td>
                    <td>{$height_ft} ft. {$height_in} in </td>
                    <td>{$row['doctor_firstname']} {$row['doctor_lastname']}</td>
                  </tr>";
        }
        ?>
    </table>

    <!-- Close the database connection -->
    <?php mysqli_close($connection); ?>
</body>
</html>
