<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("settings.php");

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$job_ref_num = $_POST["job_ref_num"] ?? "";
$first_name = $_POST["first_name"] ?? "";
$last_name = $_POST["last_name"] ?? "";
$dob = $_POST["dob"] ?? "";
$gender = $_POST["gender"] ?? "";
$street_address = $_POST["street_address"] ?? "";
$suburb_town = $_POST["suburb_town"] ?? "";
$state = $_POST["state"] ?? "";
$postcode = $_POST["postcode"] ?? "";
$email = $_POST["email"] ?? "";
$phone = $_POST["phone"] ?? "";
$other_skills = $_POST["other_skills"] ?? "";

$skills = $_POST["skill"] ?? [];

$skill_iot = in_array("iot", $skills) ? 1 : 0;
$skill_data = in_array("data", $skills) ? 1 : 0;
$skill_urban = in_array("urban", $skills) ? 1 : 0;
$skill_renewable = in_array("renewable", $skills) ? 1 : 0;
$skill_problem = in_array("problem", $skills) ? 1 : 0;
$skill_teamwork = in_array("teamwork", $skills) ? 1 : 0;

$query = "INSERT INTO eoi (
    job_ref_num,
    first_name,
    last_name,
    dob,
    gender,
    street_address,
    suburb_town,
    state,
    postcode,
    email,
    phone,
    skill_iot,
    skill_data,
    skill_urban,
    skill_renewable,
    skill_problem,
    skill_teamwork,
    other_skills
) VALUES (
    '$job_ref_num',
    '$first_name',
    '$last_name',
    '$dob',
    '$gender',
    '$street_address',
    '$suburb_town',
    '$state',
    '$postcode',
    '$email',
    '$phone',
    '$skill_iot',
    '$skill_data',
    '$skill_urban',
    '$skill_renewable',
    '$skill_problem',
    '$skill_teamwork',
    '$other_skills'
)";

$result = mysqli_query($conn, $query);

if ($result) {
    $eoi_number = mysqli_insert_id($conn);

    echo "<h1>Application submitted successfully</h1>";
    echo "<p>Your EOI number is: $eoi_number</p>";
    echo "<p>Your application status is: New</p>";
    echo "<p><a href='index.php'>Return to Home</a></p>";
} else {
    echo "<h1>Something went wrong</h1>";
    echo "<p>" . mysqli_error($conn) . "</p>";
}

mysqli_close($conn);
?>