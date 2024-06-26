<?php
//learn from w3schools.com

session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
}

//import database
include("../connection.php");
$sqlmain = "SELECT * FROM patient WHERE pemail=?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$userrow = $stmt->get_result();
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["pid"];
$username = $userfetch["pname"];

if ($_POST) {
    if (isset($_POST["booknow"])) {
        $apponum = $_POST["apponum"];
        $scheduleid = $_POST["scheduleid"];
        $date = $_POST["date"];
        $sql2 = "INSERT INTO appointment (pid, apponum, scheduleid, appodate) VALUES (?, ?, ?, ?)";
        $stmt2 = $database->prepare($sql2);
        $stmt2->bind_param("iiis", $userid, $apponum, $scheduleid, $date);
        $stmt2->execute();

        if ($stmt2->affected_rows > 0) {
            header("location: appointment.php?action=booking-added&id=".$apponum."&titleget=none");
        } else {
            die("Error: " . $stmt2->error);
        }
    }
}
?>
