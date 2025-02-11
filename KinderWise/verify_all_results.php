<?php
header('Content-Type: application/json');
include("connection.php");

try {
    $studentID = $_POST['studentID'] ?? '';
    $assessmentType = $_POST['assessmentType'] ?? '';

    $query = "
        UPDATE result r
        JOIN assessment a ON r.assessmentID = a.assessmentID
        SET r.status = 'verified'
        WHERE r.studentID = ?
        AND r.status = 'unverified'
        AND a.status = 'submitted'
    ";

    $params = [$studentID];
    $types = "i";

    if ($assessmentType) {
        $query .= " AND a.assessmentType = ?";
        $params[] = $assessmentType;
        $types .= "s";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>