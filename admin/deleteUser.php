<?php
session_start();
include("../connection.php");

// Validate role and id parameters
if (!isset($_GET['role']) || !isset($_GET['ID'])) {
    echo "<script>alert('Role and ID parameters are required'); window.location.href='admin.php';</script>";
    exit();
}

$role = $_GET['role'];
$ID = $_GET['ID'];

// Validate role value
$valid_roles = ['administrator', 'principal', 'teacher', 'parent'];
if (!in_array($role, $valid_roles)) {
    echo "<script>alert('Invalid role specified'); window.location.href='admin.php';</script>";
    exit();
}

// Special handling for teachers - update class table
if ($role == 'teacher') {
    // First, set teacherId to NULL for any classes assigned to this teacher
    $updateClassSql = "UPDATE class SET teacherId = NULL WHERE teacherId = ?";
    $stmt = mysqli_prepare($conn, $updateClassSql);
    mysqli_stmt_bind_param($stmt, "i", $ID);
    mysqli_stmt_execute($stmt);
}

// Delete the user
$stmt = mysqli_prepare($conn, "DELETE FROM $role WHERE " . $role . "ID = ?");
mysqli_stmt_bind_param($stmt, "i", $ID);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('User deleted successfully!'); window.location.href='viewUser.php?role=" . $role . "';</script>";
} else {
    echo "<script>alert('Error deleting user: " . mysqli_error($conn) . "'); window.location.href='viewUser.php?role=" . $role . "';</script>";
}
?>