<?php
// Start output buffering
ob_start();

// Clear any previous output
if (ob_get_length()) ob_clean();

// Set headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Include connection after headers
include("connection.php");

try {
    $class = $_GET['class'] ?? '';

    if (!$class) {
        throw new Exception('Class parameter is required');
    }

    // Get teacher information
    $teacherQuery = "
        SELECT t.name, t.email, t.contactNumber
        FROM teacher t
        JOIN class c ON t.teacherID = c.teacherID
        WHERE c.classCode = ?
    ";

    $stmt = $conn->prepare($teacherQuery);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $class);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $teacherResult = $stmt->get_result();
    $teacher = $teacherResult->fetch_assoc();

    if (!$teacher) {
        throw new Exception("No teacher found for class: " . $class);
    }

    // Get assessments
    $assessmentQuery = "
        SELECT 
            a.assessmentID,
            a.subjectName, 
            a.assessmentType, 
            a.description,
            COALESCE(a.status, 'Pending') as status,
            DATE_FORMAT(a.deadline, '%Y-%m-%d') as deadline
        FROM assessment a
        JOIN class c ON a.yearCode = c.yearCode
        WHERE c.classCode = ?
        ORDER BY a.deadline ASC
    ";

    $stmt = $conn->prepare($assessmentQuery);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $class);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $assessmentResult = $stmt->get_result();
    
    $assessments = [];
    while ($row = $assessmentResult->fetch_assoc()) {
        $deadline = new DateTime($row['deadline']);
        $today = new DateTime();
        
        if ($row['status'] === 'Pending' && $deadline < $today) {
            $row['status'] = 'No Submission';
        }
        $assessments[] = $row;
    }

    $response = [
        'teacher' => $teacher,
        'assessments' => $assessments
    ];
    
    // Clear any previous output
    ob_clean();
    
    echo json_encode($response);

} catch (Exception $e) {
    error_log("Error in fetch_assessments.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

// Flush and end output buffering
ob_end_flush();
?>