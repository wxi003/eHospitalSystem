<!-- This file displays a list of doctors who currently have no patients assigned to them.-->
<!-- Database connection closes at the end of the file.-->

<?php
include 'connectdb.php';

$query = "SELECT docid, firstname, lastname 
          FROM doctor 
          WHERE docid NOT IN (SELECT DISTINCT treatsdocid FROM patient)";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctors with No Patients</title>
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

    <h1>Doctors with No Patients</h1>
    <table style="border: 1px solid black; border-collapse: collapse;">
        <tr>
            <th>Doctor ID</th>
            <th>First Name</th>
            <th>Last Name</th>
        </tr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['docid']}</td>
                    <td>{$row['firstname']}</td>
                    <td>{$row['lastname']}</td>
                  </tr>";
        }
        ?>
    </table>
</body>

  <!-- Close the database connection -->
    <?php mysqli_close($connection); ?>
</html>
