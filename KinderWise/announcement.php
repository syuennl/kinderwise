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


    function loadAnnouncements()
    {
        global $conn, $teacherID;
        
        // filter announcements
        $sql = "SELECT * FROM announcement WHERE teacherID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $teacherID);
        $stmt->execute();
        $announcements = $stmt->get_result();    

        // pass results back to html
        while($row = $announcements->fetch_assoc())
        {
            echo '<tr class="announcement-row">
                <td class="announcement-title"><button class="announcement-btn" data-id=' . $row["announcementID"] . '>'. $row["announcementTitle"] .'</button></td>
                <td class="announcement-date">' . $row["postDate"] . '</td>
                <td>
                    <button class="edit" data-id=' . $row["announcementID"] . '>Edit</button>
                    <button class="delete" data-id=' . $row["announcementID"] . '>Delete</button>
                </td>
                </tr>';
        }        
    }

    function viewAnnouncement()
    {
        global $conn;

        $id = isset($_POST['id']) ? $_POST['id'] : null;
        
        if($id)
        {
            $stmt = $conn->prepare("SELECT * FROM announcement WHERE announcementID = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $announcement = $result->fetch_assoc();

            header('Content-Type: application/json');
            echo json_encode($announcement); // encode as json and return
            return;
        }


        header('Content-Type: application/json'); // tells browser the reponse is json data not plain text/html
        echo json_encode(null); // sends back json-formatted null value when no announcement found, becomes the string "null"
    }

    function editAnnouncement()  //edit subject nameeeee
    {
        global $conn;
        // get variables passed in
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $title = isset($_POST['title']) ? $_POST['title'] : null;
        $details = isset($_POST['details']) ? $_POST['details'] : null;

        if($title && $details)
        {
            // update announcement
            $stmt = $conn->prepare("UPDATE announcement SET announcementTitle = ?, details = ? WHERE announcementID = ?");
            $stmt->bind_param('ssi', $title, $details, $id);

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

    function deleteAnnouncement()
    {
        header('Content-Type: application/json');
        global $conn;
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        if($id)
        {
            $stmt = $conn->prepare("DELETE FROM announcement WHERE announcementID = ?");
            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                error_log("Execute failed: " . $stmt->error);
                echo json_encode(['success' => false, 'error' => 'Delete failed']);
                return;
            }
    
            $stmt->close();
            echo json_encode(['success' => true]);
        }
        else
        {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'No ID provided']);
        }
    }

    function addAnnouncement()
    {
        global $conn, $teacherID;
        // get parameters passed in
        $title = isset($_POST['title']) ? $_POST['title'] : null;
        $details = isset($_POST['details']) ? $_POST['details'] : null;
        $date = date("Y-m-d");

        // add announcement
        // prepared statements to prevent SQL injection
        if(!$title || !$details)
        {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false, 
                'error' => 'Title and details are required'
            ]);
            return;
        }

        try
        {
            $stmt = $conn->prepare("INSERT INTO announcement (announcementTitle, details, postDate, teacherID) 
                                    VALUES (?, ?, ?, ?)");  
                                    // ? = placeholders, instead of directly putting values in the SQL string, use these placeholders for safety
            
            if(!$stmt)
            {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("sssi", $title, $details, $date, $teacherID);
                            // each letter corresponds to one ?, specifies the type of each value that is being bind (s=str, i=int)

            if($stmt->execute()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
            } 
            else {
                throw new Exception("Execute failed: " . $stmt->error);
            }
        } 
        catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false, 
                'error' => $e->getMessage()
            ]);
            error_log("Add announcement error: " . $e->getMessage());
        } 
        finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }


    // switch to assign functions
    if(isset($_POST['action']))
    {
        switch($_POST['action'])
        {
            case 'load':
                loadAnnouncements();
                break;
            case 'view':
                viewAnnouncement();
                break;
            case 'add':
                addAnnouncement();
                break;
            case 'edit':
                editAnnouncement();
                break;
            case 'delete':
                deleteAnnouncement();
        }
    }
    
        
?>