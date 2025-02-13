<?php
header('Content-Type: application/json');
include("connection.php");

try {
    $studentID = $_GET['studentID'] ?? '';
    $assessmentType = $_GET['assessmentType'] ?? '';

    // Fetch student info
    $studentQuery = "
        SELECT name, classCode
        FROM student
        WHERE studentID = ?
    ";
    $stmt = $conn->prepare($studentQuery);
    $stmt->bind_param("i", $studentID);
    $stmt->execute();
    $studentInfo = $stmt->get_result()->fetch_assoc();

    // Debugging: Log student details
    if (!$studentInfo) {
        echo json_encode(['error' => "Student not found! ID: $studentID"]);
        exit;
    }

    // Fetch results
    $query = "
        SELECT 
            r.finalScore,
            r.status,
            a.subjectName,
            a.assessmentType
        FROM result r
        JOIN assessment a ON r.assessmentID = a.assessmentID
        WHERE r.studentID = ?
        AND a.status = 'submitted'
    ";

    $params = [$studentID];
    $types = "i";

    if ($assessmentType) {
        $query .= " AND a.assessmentType = ?";
        $params[] = $assessmentType;
        $types .= "s";
    }

    $query .= " ORDER BY a.subjectName, a.assessmentType";

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $results = [];
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }

    // Debugging: Log SQL result
    if (empty($results)) {
        echo json_encode(['error' => "No results found for Student ID: $studentID with Assessment Type: $assessmentType"]);
        exit;
    }

    echo json_encode([
        'studentInfo' => $studentInfo,
        'results' => $results
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

?>