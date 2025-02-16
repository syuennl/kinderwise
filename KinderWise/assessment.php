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

    // yearCode
    $sql = "SELECT c.yearCode FROM teacher t JOIN class c ON t.classAssigned = c.classCode WHERE t.teacherID = $teacherID";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $yearCode = $row['yearCode'];


    function filterAssessments()
    {
        global $conn, $yearCode;
        // get parameters passed in
        $subject = isset($_POST['subject']) ? $_POST['subject'] : 'All subjects';
        $semester = isset($_POST['semester']) ? $_POST['semester'] : 'All semesters';
        
        // get year shortform
        $year = '';
        if($yearCode == 'Year1')
            $year = 'Y1';
        elseif($yearCode == 'Year2')
            $year = 'Y2';
        else
            $year = 'Y3';
        
        // filter assessments
        $sql = "SELECT * FROM assessment WHERE 1=1";
        $params = array();
        $types = "";

        if($subject != 'All subjects')
        {
            $sql .= " AND subjectName = ?"; // append to sql string
            $params[] = $subject;
            $types .= "s";
        }

        if($semester != 'All semesters')
        {
            $sql .= " AND semesterCode = ?"; // append to sql string
            $params[] = 'Sem' . $semester . $year;
            $types .= "s";
        }

        // add ORDER BY clause to sort by postedOn in descending order
        $sql .= " ORDER BY postedOn DESC";

        $stmt = $conn->prepare($sql);

        if(!empty($params)) // subject / semester is selected
            $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {  // execute and see if there are errors
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            return;
        }

        $filtered_assessments = $stmt->get_result();

        // pass results back to html
        while($row = $filtered_assessments->fetch_assoc())
        {
            $date = new DateTime($row["postedOn"]) ;  
            $date = $date->format('Y-m-d');
            $typecolour = ($row["assessmentType"] == 'Midterm') ? 'green' : 'red';
            echo '<tr class="assessment-row">
                <td class="assessment-subject"><button class="subject-btn" data-id=' . $row["assessmentID"] . '>'. $row["subjectName"] .'</button></td>
                <td class="assessment-semester">' . $row["semesterCode"] . '</td>
                <td class="assessment-type" id=' . $typecolour . '>' . $row["assessmentType"] .'</td>
                <td class="assessment-date">' . $date . '</td>
                <td>
                    <button class="edit" data-id=' . $row["assessmentID"] . '>Edit</button>
                    <button class="delete" data-id=' . $row["assessmentID"] . '>Delete</button>
                </td>
                </tr>';
        }        
    }

    function viewAssessment()
    {
        global $conn;

        $id = isset($_POST['id']) ? $_POST['id'] : null;
        
        if($id)
        {
            $stmt = $conn->prepare("SELECT * FROM assessment WHERE assessmentID = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $assessment = $result->fetch_assoc();

            header('Content-Type: application/json');
            echo json_encode($assessment); // encode as json and return
            return;
        }


        header('Content-Type: application/json'); // tells browser the reponse is json data not plain text/html
        echo json_encode(null); // sends back json-formatted null value when no assessment found, becomes the string "null"
    }

    function editAssessment()  //edit subject nameeeee
    {
        global $conn, $yearCode;
        // get variables passed in
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $subject = isset($_POST['subject']) ? $_POST['subject'] : null;
        $sem = isset($_POST['semester']) ? $_POST['semester'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
 
        if($subject && $sem && $description)
        {
            $type = ($sem == 1) ? 'Midterm' : 'Finals'; // determine type
           
            // get semesterCode in the right format
            $year = '';
 
            if($yearCode === 'Year1') 
                $year = 'Y1';
            elseif($yearCode === 'Year2')
                $year = 'Y2';
            elseif($yearCode === 'Year3')
                $year = 'Y3';
 
            $semester = 'Sem'. $sem . $year;  // format semester value to be Sem_Y_
            $stmt = $conn->prepare("SELECT semesterCode FROM semester WHERE semesterCode = ?");
            $stmt->bind_param('s', $semester);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $semesterCode = $row['semesterCode'];
 
            // update assessment
            $stmt = $conn->prepare("UPDATE assessment SET subjectName = ?, semesterCode = ?, assessmentType = ?, description = ? WHERE assessmentID = ?");
            $stmt->bind_param('ssssi', $subject, $semesterCode, $type, $description, $id);
 
            if($stmt->execute())
            {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
            } else
            {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $conn->error]);
            }
 
            $stmt->close();
        }
        else{
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        }
    }

    function deleteAssessment()
    {
        header('Content-Type: application/json');
        global $conn;
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        if($id)
        {
            // check if any results linked with assessment
            // claude pls help me check in this part
            $stmt = $conn->prepare("SELECT * FROM result WHERE assessmentID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0) // if there's results linked with current assessment
            {
                // first delete from result table
                $stmt1 = $conn->prepare("DELETE FROM result WHERE assessmentID = ?");
                $stmt1->bind_param("i", $id);
                $stmt1->execute();
                $stmt1->close();
            }
            
            // then delete from assessment table
            $stmt2 = $conn->prepare("DELETE FROM assessment WHERE assessmentID = ?");
            $stmt2->bind_param("i", $id);
           
            if($stmt2->execute())
            {
                echo json_encode(['success' => true]);
            }
            else
            {
                // header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $conn->error]);
            }
 
            $stmt2->close();
            $stmt->close();
        }
        else
        {
            // header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'No ID provided']);
        }
    }

    function addAssessment()
    {
        global $conn, $teacherID, $yearCode;
        // get parameters passed in
        $subject = isset($_POST['subject']) ? $_POST['subject'] : null;
        $sem = isset($_POST['semester']) ? $_POST['semester'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $type = ($sem == 1) ? 'Midterm' : 'Finals';  // determine type

        // get semesterCode in correct format
        $year = '';
        if($yearCode == 'Year1') 
            $year = 'Y1';
        elseif($yearCode == 'Year2')
            $year = 'Y2';
        else
            $year = 'Y3';

        $semester = 'Sem'. $sem . $year;  // format semester value to be Sem_Y_
        $stmt = $conn->prepare("SELECT semesterCode FROM semester WHERE semesterCode = ?");
        $stmt->bind_param('s', $semester);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $semesterCode = $row['semesterCode'];

        // add assessment
        // prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO assessment (assessmentType, teacherID, subjectName, semesterCode, yearCode, description, deadline, status) 
                                VALUES (?, ?, ?, ?, ?, ?, '2025-01-01', 'no submission')");  /**ddl*** */
                                // ? = placeholders, instead of directly putting values in the SQL string, use these placeholders for safety
        $stmt->bind_param("sissss", $type, $teacherID, $subject, $semesterCode, $yearCode, $description);
                        // each letter corresponds to one ?, specifies the type of each value that is being bind (s=str, i=int)

        if($stmt->execute()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    
        $stmt->close();
    }

    function getSubjects()
    {
        global $yearCode;
        $year = '';
 
        if($yearCode === 'Year1') 
            $year = 'Y1';
        elseif($yearCode === 'Year2')
            $year = 'Y2';
        elseif($yearCode === 'Year3')
            $year = 'Y3';

        $subjects = ["BM" => 'Bahasa Malaysia '.$year, "BI" => 'English '.$year, "BC" => 'Mandarin '.$year, "MT" => 'Mathematics '.$year, "SC" => 'Science '.$year];
        return $subjects;        
    }


    // switch to assign functions
    if(isset($_POST['action']))
    {
        switch($_POST['action'])
        {
            case 'filter':
                filterAssessments();
                break;
            case 'view':
                viewAssessment();
                break;
            case 'add':
                addAssessment();
                break;
            case 'edit':
                editAssessment();
                break;
            case 'delete':
                deleteAssessment();
                break;
            case 'subjects':
                header('Content-Type: application/json');
                echo json_encode(getSubjects());
                break;
        }
    }
    
        
?>
