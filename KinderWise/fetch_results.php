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

// For debugging, let's see what's actually being output
$debug = true;

try {
    // Get and validate inputs
    $class = $_GET['class'] ?? '';
    $subject = $_GET['subject'] ?? '';
    $assessment = $_GET['assessment'] ?? '';

    if (!$class || !$subject || !$assessment) {
        echo json_encode([0, 0, 0, 0, 0, 0]);
        exit;
    }

    // Define grade ranges
    $grades = ["A" => [80, 100], "B" => [60, 79], "C" => [50, 59], "D" => [45, 49], "E" => [40, 44], "G" => [0, 39]];
    $gradeCounts = ["A" => 0, "B" => 0, "C" => 0, "D" => 0, "E" => 0, "G" => 0];

    // Prepare and execute the query
    $sql = "SELECT r.finalScore 
            FROM result r 
            JOIN student s ON r.studentID = s.studentID 
            JOIN assessment a ON r.assessmentID = a.assessmentID 
            WHERE s.classCode = ? 
            AND a.subjectName = ? 
            AND a.assessmentType = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sss", $class, $subject, $assessment);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $totalStudents = $result->num_rows;

    // Calculate grade distribution
    while ($row = $result->fetch_assoc()) {
        $score = floatval($row['finalScore']);
        foreach ($grades as $grade => $range) {
            if ($score >= $range[0] && $score <= $range[1]) {
                $gradeCounts[$grade]++;
                break;
            }
        }
    }

    // Convert counts to percentages
    $gradePercentages = array_map(function($count) use ($totalStudents) {
        return round(($count / $totalStudents) * 100, 1);
    }, $gradeCounts);

    $jsonData = json_encode(array_values($gradePercentages));
    
    if ($debug) {
        // Log the raw output
        error_log("Raw output: " . ob_get_contents());
        ob_clean();
    }
    
    echo $jsonData;

} catch (Exception $e) {
    error_log("Error in fetch_results.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

// Flush and end output buffering
ob_end_flush();
?>
