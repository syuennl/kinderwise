<?php
    session_start();
    include("connection.php");
    
    header('Content-Type: text/html; charset=utf-8');
    // content-type:  tells browser that the content being sent is HTML text (not an image, PDF, or other type of file)
    // charset: specifies that the text is encoded using UTF-8 character encoding

    // if not logged in, redirect to login page
    if(!isset($_SESSION['teacherID']))
    {
        header('Location: login.php'); // if not logged in, direct to login page
        exit();
    }
    
    // teacherID
    $teacherID = $_SESSION['teacherID'];

    // class assigned
    $sql = "SELECT classAssigned FROM teacher t WHERE t.teacherID = $teacherID";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $classAssigned = $row['classAssigned'];

    // yearCode
    $sql = "SELECT c.yearCode FROM teacher t JOIN class c ON t.classAssigned = c.classCode WHERE t.teacherID = $teacherID";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $yearCode = $row['yearCode'];


    function filterGrades()
    {
        global $conn, $classAssigned, $yearCode, $teacherID;
        // get parameters passed in
        $semester = isset($_POST['semester']) ? $_POST['semester'] : 'All';
        
        // get year shortform
        $year = '';
        if($yearCode == 'Year1') 
            $year = 'Y1';
        elseif($yearCode == 'Year2')
            $year = 'Y2';
        else
            $year = 'Y3';
        
        // filter gradings
        $sql = "SELECT s.studentID, s.name,
                MAX(CASE WHEN a.subjectName = ? THEN r.finalScore END) AS Bahasa_Malaysia,
                MAX(CASE WHEN a.subjectName = ? THEN r.finalScore END) AS English,
                MAX(CASE WHEN a.subjectName = ? THEN r.finalScore END) AS Mandarin,
                MAX(CASE WHEN a.subjectName = ? THEN r.finalScore END) AS Mathematics,
                MAX(CASE WHEN a.subjectName = ? THEN r.finalScore END) AS Science
                FROM student s
                JOIN result r ON r.studentID = s.studentID
                JOIN assessment a ON r.assessmentID = a.assessmentID
                JOIN teacher t ON t.classAssigned = s.classCode
                WHERE a.yearCode = ?
                AND t.teacherID = ?";
        
        $params = [
            'Bahasa Malaysia ' . $year,
            'English ' . $year,
            'Mandarin ' . $year,
            'Mathematics ' . $year,
            'Science ' . $year,
            $yearCode, $teacherID
        ];

        $types = "ssssssi";

        
        $sql .= " AND a.semesterCode = ?"; // append to sql string
        $params[] = 'Sem' . $semester . $year;
        $types .= "s";
        

        $sql .= " GROUP BY s.studentID, s.name ORDER BY s.studentID";

        $stmt = $conn->prepare($sql);

        if(!empty($params)) 
            $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {  // execute and see if there are errors
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            return;
        }

        $filtered_grading = $stmt->get_result();
        $index = 1;
        while($row = $filtered_grading->fetch_assoc())
        {
            // dash for empty scores
            $bm = $row["Bahasa_Malaysia"] ?? '-';
            $en = $row["English"] ?? '-';
            $md = $row["Mandarin"] ?? '-';
            $mt = $row["Mathematics"] ?? '-';
            $sc = $row["Science"] ?? '-';
            
            echo '<form class="grade-form" action="grading.php" method="POST">
                <tr class="grading-row">
                    <td class="grading-index">' . $index++ . '</td>
                    <td class="student-name">' . $row["name"] . '
                        <input class="studentId" type="hidden" name="studentID" value="' . $row["studentID"] . '">
                    </td>
                    <td><input type="text" name="BM" class="grade-input" value="' . htmlspecialchars($bm) . '"></td>
                    <td><input type="text" name="EN" class="grade-input" value="' . htmlspecialchars($en) . '"></td>
                    <td><input type="text" name="MD" class="grade-input" value="' . htmlspecialchars($md) . '"></td>
                    <td><input type="text" name="MT" class="grade-input" value="' . htmlspecialchars($mt) . '"></td>
                    <td><input type="text" name="SC" class="grade-input" value="' . htmlspecialchars($sc) . '"></td>
                </tr>
            </form>';
        }   
    }

    function saveGrades() {
        global $conn, $yearCode;
    
        // Get and validate input parameters
        $sem = isset($_POST['semester']) ? $_POST['semester'] : null;        
        $studentIDs = isset($_POST['studentIDs']) ? json_decode($_POST['studentIDs'], true) : null;
        $grades = isset($_POST['grades']) ? json_decode($_POST['grades'], true) : null;
    
        if (!$sem || !$studentIDs || !$grades) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Missing required data']);
            return;
        }
    
        // Format year code
        $year = '';
        switch($yearCode) {
            case 'Year1':
                $year = 'Y1';
                break;
            case 'Year2':
                $year = 'Y2';
                break;
            case 'Year3':
                $year = 'Y3';
                break;
            default:
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Invalid year code']);
                return;
        }
    
        $semester = 'Sem' . $sem . $year;
    
        try {
            // Begin transaction
            $conn->begin_transaction();
            error_log("Started transaction");
        
            // First, prepare the SELECT statement to check existence
            $checkSql = "SELECT resultID FROM result
                        WHERE studentID = ? AND assessmentID = ?";
            $checkStmt = $conn->prepare($checkSql);
            if (!$checkStmt) {
                throw new Exception("Error preparing check statement: " . $conn->error);
            }
        
            // Prepare INSERT statement
            $insertSql = "INSERT INTO result (studentID, assessmentID, finalScore, status)
                        VALUES (?, ?, ?, 'unverified')";
            $insertStmt = $conn->prepare($insertSql);
            if (!$insertStmt) {
                throw new Exception("Error preparing insert statement: " . $conn->error);
            }
        
            // Prepare UPDATE statement
            $updateSql = "UPDATE result
                        SET finalScore = ?
                        WHERE studentID = ? AND assessmentID = ?";
            $updateStmt = $conn->prepare($updateSql);
            if (!$updateStmt) {
                throw new Exception("Error preparing update statement: " . $conn->error);
            }
        
            foreach ($studentIDs as $studentID) {
                if (!isset($grades[$studentID])) {
                    continue;
                }
            
                foreach ($grades[$studentID] as $subject => $grade) {
                    // Skip empty or '-' grades
                    if ($grade === '-' || trim($grade) === '') {
                        $grade = NULL;
                        // continue;
                    }
                    elseif (!is_numeric($grade) || $grade < 0 || $grade > 100) {  // Validate grade
                        throw new Exception("Invalid grade value: $grade for student $studentID in $subject");
                    }
                
                    // Format subject name to match database
                    $subjectName = $subject . " " . $year;
                    error_log("Processing grade $grade for student $studentID in $subjectName");
                
                    // Get the assessmentID for this subject and semester
                    $assessmentQuery = "SELECT assessmentID FROM assessment
                                    WHERE subjectName = ? AND semesterCode = ?";
                    $assessmentStmt = $conn->prepare($assessmentQuery);
                    $assessmentStmt->bind_param("ss", $subjectName, $semester);
                    $assessmentStmt->execute();
                    $assessmentResult = $assessmentStmt->get_result();
                    $assessmentRow = $assessmentResult->fetch_assoc();
                
                    if (!$assessmentRow) {
                        throw new Exception("Assessment not found for subject $subjectName");
                    }
                
                    $assessmentID = $assessmentRow['assessmentID'];
                
                    // Check if record exists
                    $checkStmt->bind_param("ii", $studentID, $assessmentID);
                    $checkStmt->execute();
                    $checkResult = $checkStmt->get_result();
                
                    if ($checkResult->num_rows > 0) {
                        // Update existing record
                        if($grade == NULL)
                            $updateStmt->bind_param("sii", $grade, $studentID, $assessmentID);
                        else
                            $updateStmt->bind_param("dii", $grade, $studentID, $assessmentID);

                        if (!$updateStmt->execute()) {
                            throw new Exception("Error executing update: " . $updateStmt->error);
                        }
                    } else {
                        // Insert new record
                        if($grade == NULL)
                            $insertStmt->bind_param("iis", $studentID, $assessmentID, $grade);
                        else
                            $insertStmt->bind_param("iid", $studentID, $assessmentID, $grade);

                        if (!$insertStmt->execute()) {
                            throw new Exception("Error executing insert: " . $insertStmt->error);
                        }
                    }
                
                    $assessmentStmt->close();
                }
            }
        
            $checkStmt->close();
            $insertStmt->close();
            $updateStmt->close();
            $conn->commit();
            error_log("Transaction committed successfully");
        
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        
            } catch (Exception $e) {
                $conn->rollback();
                error_log("Error in saveGrades: " . $e->getMessage());
            
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            }
    }


    // switch to assign functions
    if(isset($_POST['action']))
    {
        switch($_POST['action'])
        {
            case 'filter':
                filterGrades();
                break;
            case 'save':
                saveGrades();
                break;
        }
    }
    
        
?>