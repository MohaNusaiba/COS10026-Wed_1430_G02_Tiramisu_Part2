<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("settings.php");

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$where_parts = [];

if (isset($_GET["job_ref_num"]) && $_GET["job_ref_num"] != "") {
    $job_ref_num = $_GET["job_ref_num"];
    $where_parts[] = "job_ref_num = '$job_ref_num'";
}

if (isset($_GET["first_name"]) && $_GET["first_name"] != "") {
    $first_name = $_GET["first_name"];
    $where_parts[] = "first_name LIKE '%$first_name%'";
}

if (isset($_GET["last_name"]) && $_GET["last_name"] != "") {
    $last_name = $_GET["last_name"];
    $where_parts[] = "last_name LIKE '%$last_name%'";
}

$where = "";

if (count($where_parts) > 0) {
    $where = "WHERE " . implode(" AND ", $where_parts);
}

$query = "SELECT * FROM eoi $where";


$result = mysqli_query($conn, $query);
?>

<?php include 'header.inc'; ?>

<main>
    <h1>Manage Expressions of Interest</h1>
    <h2>Search by Job Reference</h2>

<h2>Search EOIs</h2>

<form method="get" action="manage.php">
    <p>
        <label for="job_ref_num">Job Reference Number:</label>
        <input type="text" name="job_ref_num" id="job_ref_num">
    </p>

    <p>
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name">
    </p>

    <p>
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name">
    </p>

    <input type="submit" value="Search">
</form>

<p><a href="manage.php">Show all EOIs</a></p>

    <h2>All EOIs</h2>

    <table border="1">
        <tr>
            <th>EOI Number</th>
            <th>Job Reference</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Date of Birth</th>
            <th>Gender</th>
            <th>Street Address</th>
            <th>Suburb/Town</th>
            <th>State</th>
            <th>Postcode</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
        </tr>

        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["EOInumber"] . "</td>";
                echo "<td>" . $row["job_ref_num"] . "</td>";
                echo "<td>" . $row["first_name"] . "</td>";
                echo "<td>" . $row["last_name"] . "</td>";
                echo "<td>" . $row["dob"] . "</td>";
                echo "<td>" . $row["gender"] . "</td>";
                echo "<td>" . $row["street_address"] . "</td>";
                echo "<td>" . $row["suburb_town"] . "</td>";
                echo "<td>" . $row["state"] . "</td>";
                echo "<td>" . $row["postcode"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["phone"] . "</td>";
                echo "<td>" . $row["Status"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr>";
            echo "<td colspan='13'>No EOIs found.</td>";
            echo "</tr>";
        }
        ?>
    </table>
</main>

<?php
mysqli_close($conn);
include 'footer.inc';
?>