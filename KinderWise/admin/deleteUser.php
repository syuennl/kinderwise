<?php
// deleteUser.php - Complete replacement with all FK checks
session_start();
include("../connection.php");

if (!isset($_GET['role']) || !isset($_GET['ID'])) {
    echo "<script>alert('Role and ID parameters are required'); window.location.href='admin.php';</script>";
    exit();
}

$role = $_GET['role'];
$ID = $_GET['ID'];

$valid_roles = ['administrator', 'principal', 'teacher', 'parent'];
if (!in_array($role, $valid_roles)) {
    echo "<script>alert('Invalid role specified'); window.location.href='admin.php';</script>";
    exit();
}

mysqli_begin_transaction($conn);

try {
    $canDelete = true;
    $errorMessage = "";

    // Check dependencies based on role
    switch($role) {
        case 'teacher':
            // Check for students in teacher's class
            $studentQuery = "SELECT COUNT(*) as count FROM student WHERE classCode IN (SELECT classCode FROM class WHERE teacherId = ?)";
            $stmt = mysqli_prepare($conn, $studentQuery);
            mysqli_stmt_bind_param($stmt, "i", $ID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $studentCount = mysqli_fetch_assoc($result)['count'];

            // Check for assessments created by teacher
            $assessmentQuery = "SELECT COUNT(*) as count FROM assessment WHERE teacherId = ?";
            $stmt = mysqli_prepare($conn, $assessmentQuery);
            mysqli_stmt_bind_param($stmt, "i", $ID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $assessmentCount = mysqli_fetch_assoc($result)['count'];

            // Check for announcements made by teacher
            $announcementQuery = "SELECT COUNT(*) as count FROM announcement WHERE teacherId = ?";
            $stmt = mysqli_prepare($conn, $announcementQuery);
            mysqli_stmt_bind_param($stmt, "i", $ID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $announcementCount = mysqli_fetch_assoc($result)['count'];

            if ($studentCount > 0 || $assessmentCount > 0 || $announcementCount > 0) {
                $canDelete = false;
                $errorMessage = "Cannot delete teacher: Has $studentCount students, $assessmentCount assessments, and $announcementCount announcements";
            } else {
                // Update class table to remove teacher reference
                $updateClassSql = "UPDATE class SET teacherId = NULL WHERE teacherId = ?";
                $stmt = mysqli_prepare($conn, $updateClassSql);
                mysqli_stmt_bind_param($stmt, "i", $ID);
                mysqli_stmt_execute($stmt);
            }
            break;

        case 'parent':
            // Check for students associated with parent
            $childrenQuery = "SELECT COUNT(*) as count FROM student WHERE parentId = ?";
            $stmt = mysqli_prepare($conn, $childrenQuery);
            mysqli_stmt_bind_param($stmt, "i", $ID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $childrenCount = mysqli_fetch_assoc($result)['count'];

            if ($childrenCount > 0) {
                $canDelete = false;
                $errorMessage = "Cannot delete parent: Has $childrenCount children enrolled";
            }
            break;
    }

    if ($canDelete) {
        $stmt = mysqli_prepare($conn, "DELETE FROM $role WHERE " . $role . "ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $ID);
        mysqli_stmt_execute($stmt);
        mysqli_commit($conn);
        echo "<script>alert('User deleted successfully!'); window.location.href='viewUser.php?role=" . $role . "';</script>";
    } else {
        mysqli_rollback($conn);
        echo "<script>alert('$errorMessage'); window.location.href='viewUser.php?role=" . $role . "';</script>";
    }
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "<script>alert('Error deleting user: " . mysqli_error($conn) . "'); window.location.href='viewUser.php?role=" . $role . "';</script>";
}
