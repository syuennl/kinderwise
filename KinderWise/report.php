<?php
    session_start();
    include("connection.php");

    if(!isset($_SESSION['teacherID']))
    {
        header('Location: login.php'); // if not logged in, direct to login page
        exit();
    }
    
    // teacherID
    $teacherID = $_SESSION['teacherID'];

    // yearCode
    $sql = "SELECT c.yearCode FROM teacher t JOIN class c ON t.classAssigned = c.classCode WHERE t.teacherID = $teacherID";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $yearCode = $row['yearCode'];

    if($yearCode == "Year1")
        $year = 'Y1';
    elseif($yearCode == "Year2")
        $year = 'Y2';
    else
        $year = 'Y3';


    $sem1 = 'Sem1'. $year;
    $sem2 = 'Sem2'. $year;

    // get average arrays for 2 semesters
    $sem1_averages = getAverageMarks($conn, $sem1);
    $sem2_averages = getAverageMarks($conn, $sem2);

    // return as JSON
    header('Content-Type: application/json');
    echo json_encode([
        'semester1' => $sem1_averages,
        'semester2' => $sem2_averages
    ]);

    function getAverageMarks($conn, $semester)
    {
        global $teacherID, $year;
        $subjects = ['Bahasa Malaysia '.$year, 'English '.$year, 'Mandarin '.$year, 'Mathematics '.$year, 'Science '.$year];
        $average = [];

        foreach ($subjects as $subject) 
        {

            // get avg assessment scores for each subject
            $sql = "SELECT AVG(r.finalScore) as avgScore 
                   FROM result r
                   JOIN assessment a ON r.assessmentID = a.assessmentID
                   WHERE a.teacherID = ? 
                   AND a.semesterCode = ? 
                   AND a.subjectName = ?";
                      
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $teacherID, $semester, $subject);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            // record average if it exists
            $average[$subject] = $row['avgScore'] !== null ? round($row['avgScore'], 2) : 0;
        }

        return $average;
    }
?>