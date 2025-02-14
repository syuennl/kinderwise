<?php
session_start();
include("../connection.php");

if (!isset($_GET['entity']) || !isset($_GET['pk'])) {
    echo "<script>alert('Entity and pk parameters are required'); window.location.href='admin.php';</script>";
    exit();
}

$entity = $_GET['entity'];
$pk = $_GET['pk'];

$valid_entities = ['year', 'semester', 'subject', 'class', 'student', 'assessment', 'result', 'announcement'];
if (!in_array($entity, $valid_entities)) {
    echo "<script>alert('Invalid entity specified'); window.location.href='admin.php';</script>";
    exit();
}

mysqli_begin_transaction($conn);

try {
    $canDelete = true;
    $errorMessage = "";

    // Check dependencies based on entity
    switch($entity) {
        case 'year':
            // Check for dependent semesters
            $semesterQuery = "SELECT COUNT(*) as count FROM semester WHERE yearCode = ?";
            $stmt = mysqli_prepare($conn, $semesterQuery);
            mysqli_stmt_bind_param($stmt, "s", $pk);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $semesterCount = mysqli_fetch_assoc($result)['count'];

            // Check for dependent subjects
            $subjectQuery = "SELECT COUNT(*) as count FROM subject WHERE yearCode = ?";
            $stmt = mysqli_prepare($conn, $subjectQuery);
            mysqli_stmt_bind_param($stmt, "s", $pk);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $subjectCount = mysqli_fetch_assoc($result)['count'];

            // Check for dependent classes
            $classQuery = "SELECT COUNT(*) as count FROM class WHERE yearCode = ?";
            $stmt = mysqli_prepare($conn, $classQuery);
            mysqli_stmt_bind_param($stmt, "s", $pk);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $classCount = mysqli_fetch_assoc($result)['count'];

            // Check for dependent students
            $studentQuery = "SELECT COUNT(*) as count FROM student WHERE yearCode = ?";
            $stmt = mysqli_prepare($conn, $studentQuery);
            mysqli_stmt_bind_param($stmt, "s", $pk);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $studentCount = mysqli_fetch_assoc($result)['count'];

            // Check for dependent assessments
            $assessmentQuery = "SELECT COUNT(*) as count FROM assessment WHERE yearCode = ?";
            $stmt = mysqli_prepare($conn, $assessmentQuery);
            mysqli_stmt_bind_param($stmt, "s", $pk);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $assessmentCount = mysqli_fetch_assoc($result)['count'];

            if ($semesterCount > 0 || $subjectCount > 0 || $classCount > 0 || $studentCount > 0 || $assessmentCount > 0) {
                $canDelete = false;
                $errorMessage = "Cannot delete year: Has $semesterCount semesters, $subjectCount subjects, $classCount classes, $studentCount students, and $assessmentCount assessments";
            }
            break;

        case 'semester':
            // Check for dependent assessments
            $assessmentQuery = "SELECT COUNT(*) as count FROM assessment WHERE semesterCode = ?";
            $stmt = mysqli_prepare($conn, $assessmentQuery);
            mysqli_stmt_bind_param($stmt, "s", $pk);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $assessmentCount = mysqli_fetch_assoc($result)['count'];

            if ($assessmentCount > 0) {
                $canDelete = false;
                $errorMessage = "Cannot delete semester: Has $assessmentCount assessments";
            }
            break;

        case 'subject':
            // Check for dependent assessments
            $assessmentQuery = "SELECT COUNT(*) as count FROM assessment WHERE subjectName = ?";
            $stmt = mysqli_prepare($conn, $assessmentQuery);
            mysqli_stmt_bind_param($stmt, "s", $pk);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $assessmentCount = mysqli_fetch_assoc($result)['count'];

            if ($assessmentCount > 0) {
                $canDelete = false;
                $errorMessage = "Cannot delete subject: Has $assessmentCount assessments";
            }
            break;

        case 'class':
            // Check for students in class
            $studentQuery = "SELECT COUNT(*) as count FROM student WHERE classCode = ?";
            $stmt = mysqli_prepare($conn, $studentQuery);
            mysqli_stmt_bind_param($stmt, "s", $pk);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $studentCount = mysqli_fetch_assoc($result)['count'];

            if ($studentCount > 0) {
                $canDelete = false;
                $errorMessage = "Cannot delete class: Has $studentCount students";
            }
            break;

        case 'student':
            // Check for results
            $resultQuery = "SELECT COUNT(*) as count FROM result WHERE studentpk = ?";
            $stmt = mysqli_prepare($conn, $resultQuery);
            mysqli_stmt_bind_param($stmt, "i", $pk);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $resultCount = mysqli_fetch_assoc($result)['count'];

            if ($resultCount > 0) {
                $canDelete = false;
                $errorMessage = "Cannot delete student: Has $resultCount results";
            }
            break;

        case 'assessment':
            // Check for results
            $resultQuery = "SELECT COUNT(*) as count FROM result WHERE assessmentID = ?";
            $stmt = mysqli_prepare($conn, $resultQuery);
            mysqli_stmt_bind_param($stmt, "i", $pk);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $resultCount = mysqli_fetch_assoc($result)['count'];

            if ($resultCount > 0) {
                $canDelete = false;
                $errorMessage = "Cannot delete assessment: Has $resultCount results";
            }
            break;

        // Result and Announcement have no dependencies to check
    }

    if ($canDelete) {
        // Determine the primary key column name based on entity
        $pkColumn = match($entity) {
            'subject' => 'subjectName',
            'year', 'semester', 'class' => $entity . 'Code',
            default => $entity . 'ID'
        };
        
        // Determine parameter type for binding based on schema
        $paramType = in_array($entity, ['year', 'semester', 'subject', 'class']) ? 's' : 'i';
        
        $stmt = mysqli_prepare($conn, "DELETE FROM $entity WHERE $pkColumn = ?");
        mysqli_stmt_bind_param($stmt, $paramType, $pk);
        mysqli_stmt_execute($stmt);
        mysqli_commit($conn);
        echo "<script>alert('" . ucfirst($entity) . " deleted successfully!'); window.location.href='viewSetting.php?entity=$entity';</script>";
    } else {
        mysqli_rollback($conn);
        echo "<script>alert('$errorMessage'); window.location.href='admin.php';</script>";
    }
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "<script>alert('Error deleting " . $entity . ": " . mysqli_error($conn) . "'); window.location.href='viewSetting.php?entity=$entity';</script>";
}
?>