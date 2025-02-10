<?php

    // session_start();
    // include("connection.php");

    // include("database.php");
    header('Content-Type: text/html; charset=utf-8');
    // content-type:  tells browser that the content being sent is HTML text (not an image, PDF, or other type of file)
    // charset: specifies that the text is encoded using UTF-8 character encoding

    // temp db
    $assessments = [ // *id
    ['id' => 1, 'assessmentTitle' => 'English', 'semester' => '2', 'assessmentType' => 'Finals', 'postedOn' => '2025-10-08', 'details' => '1st sdcknsdckd'],
    ['id' => 2, 'assessmentTitle' => 'Mandarin', 'semester' => '2', 'assessmentType' => 'Finals', 'postedOn' => '2025-10-01', 'details' => '2nd sdcknsdckd'],
    ['id' => 3, 'assessmentTitle' => 'Science', 'semester' => '1', 'assessmentType' => 'Midterm', 'postedOn' => '2025-03-28', 'details' => '3rd sdcknsdckd'],
    ['id' => 4, 'assessmentTitle' => 'English', 'semester' => '1', 'assessmentType' => 'Midterm', 'postedOn' => '2025-02-14', 'details' => '4th sdcknsdckd'],
    ['id' => 5, 'assessmentTitle' => 'Maths', 'semester' => '1', 'assessmentType' => 'Midterm', 'postedOn' => '2025-01-24', 'details' => '5th sdcknsdckd']
    ];
    
    function filterAssessments()
    {
        global $assessments;
        $subject = isset($_POST['subject']) ? $_POST['subject'] : 'All';
        $semester = isset($_POST['semester']) ? $_POST['semester'] : 'All';

        $filtered_assessments = array_filter($assessments, function($a) use ($subject, $semester)
        {
            $subject_match = ($subject == 'All' || $a['assessmentTitle'] == $subject); // if selection is all / db element matches selection
            $semester_match = ($semester == 'All' || $a['semester'] == $semester);
            return $subject_match && $semester_match; // add object to array
        });


        foreach($filtered_assessments as $f)
        {
            echo '<tr class="assessment-row">
                <td class="assessment-title"><button class="title-btn" data-id=' . $f["id"] . '>'. $f["assessmentTitle"] .'</button></td>
                <td class="assessment-semester">' . $f["semester"] . '</td>
                <td class="assessment-type">' . $f["assessmentType"] .'</td>
                <td class="assessment-date">' . $f["postedOn"] . '</td>
                <td>
                    <button class="edit" data-id=' . $f["id"] . '>Edit</button>
                    <button class="delete" data-id=' . $f["id"] . '>Delete</button>
                </td>
                </tr>';
        }        
    }

    function viewAssessment()
    {
        global $assessments;
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        if($id)
        {
            foreach($assessments as $a)
            {
                if($a['id'] ==  $id)
                {
                    header('Content-Type: application/json');
                    echo json_encode($a); // encode as json and return
                    return;
                }
            }
        }

        header('Content-Type: application/json'); // tells browser the reponse is json data not plain text/html
        echo json_encode(null); // sends back json-formatted null value when no assessment found, becomes the string "null"
    }

    function editAssessment()
    {
        global $assessments;
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $semester = isset($_POST['semester']) ? $_POST['semester'] : null;
        $details = isset($_POST['details']) ? $_POST['details'] : null;

        if($id && $semester && $details)
        {
            foreach($assessments as $a)
            {
                if($a['id'] == $id)
                {
                    $a['semester'] = $semester;
                    if($semester == 1)
                        $a['assessmentType'] = 'Midterm';
                    else
                        $a['assessmentType'] = 'Finals';
                    $a['details'] = $details;

                    header('Content-Type: application/json');
                    echo json_encode(['success' => true]);
                    return;
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
    }

    function deleteAssessment()
    {
        global $assessments;
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        if($id)
        {
            foreach($assessments as $a)
            {
                if($a['id'] == $id)
                {
                    unset($assessments[$id]); // remove assessment
                    $assessments = array_values($assessments); // reindex array to remove gaps
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true]);
                    return;
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
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
            case 'edit':
                editAssessment();
                break;
            case 'delete':
                deleteAssessment();
        }
    }
    
        
?>