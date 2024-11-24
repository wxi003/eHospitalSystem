<!-- This file lists all doctors and their assigned patients. -->	
<!-- Database connection closes at the end of the file.-->


<?php
include 'connectdb.php';

$query = "SELECT doctor.firstname AS doctor_firstname, doctor.lastname AS doctor_lastname, 
          patient.firstname AS patient_firstname, patient.lastname AS patient_lastname 
          FROM doctor 
          LEFT JOIN patient ON doctor.docid = patient.treatsdocid";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctors and Their Patients</title>
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
    
    <h1>Doctors and Their Patients</h1>
    <table style="border: 1px solid black; border-collapse: collapse;">
        <tr>
            <th>Doctor Name</th>
            <th>Patient Name</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['doctor_firstname']} {$row['doctor_lastname']}</td>
                    <td>{$row['patient_firstname']} {$row['patient_lastname']}</td>
                  </tr>";
        }
        ?>
    </table>
</body>
    <!-- Close the database connection -->
    <?php mysqli_close($connection); ?>
</html>
