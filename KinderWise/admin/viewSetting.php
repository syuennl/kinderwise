<?php
session_start();
include("../connection.php");
include("sidebar.php");

// Validate entity parameter
if(!isset($_GET['entity'])) {
    echo "<script>alert('Entity parameter is missing'); window.location.href='admin.php';</script>";
    exit();
}

$entity = $_GET['entity'];
$valid_entities = ['year', 'semester', 'subject', 'class', 'student', 'assessment', 'result', 'announcement'];
if(!in_array($entity, $valid_entities)) {
    echo "<script>alert('Invalid entity specified'); window.location.href='admin.php';</script>";
    exit();
}

// Define columns for each entity
$columns = [
    'year' => ['yearCode', 'description'],
    'semester' => ['semesterCode', 'yearCode', 'startDate', 'endDate'],
    'subject' => ['subjectName', 'yearCode'],
    'class' => ['classCode', 'teacherID', 'yearCode', 'classCapacity'],
    'student' => ['studentID', 'name', 'age', 'birthday', 'gender', 'parentID', 'classCode', 'yearCode'],
    'assessment' => ['assessmentID', 'assessmentType', 'teacherID', 'subjectName', 'semesterCode', 'yearCode', 'description', 'deadline', 'status'],
    'result' => ['resultID', 'finalScore', 'studentID', 'assessmentID', 'status'],
    'announcement' => ['announcementID', 'announcementTitle', 'details', 'postDate', 'teacherID']
];

// Define display names for each entity column
$columns_names = [
    'year' => ['Year Code', 'Description'],
    'semester' => ['Semester Code', 'Year Code', 'Start Date', 'End Date'],
    'subject' => ['Subject Name', 'Year Code'],
    'class' => ['Class Code', 'Teacher', 'Year Code', 'Capacity'],
    'student' => ['ID', 'Name', 'Age', 'Birthday', 'Gender', 'Parent', 'Class Code', 'Year Code'],
    'assessment' => ['ID', 'Type', 'Teacher', 'Subject Name', 'Semester Code', 'Year Code', 'Description', 'Deadline', 'Status'],
    'result' => ['ID', 'Score', 'Student', 'Assessment', 'Status'],
    'announcement' => ['ID', 'Title', 'Details', 'Post Date', 'Teacher']
];

// Build the query with any necessary joins
$sql = "SELECT " . implode(", ", $columns[$entity]) . " FROM $entity";

// Add joins for foreign key references
switch($entity) {
    case 'class':
        $sql = "SELECT c.*, t.name as teacherName 
                FROM class c 
                LEFT JOIN teacher t ON c.teacherID = t.teacherID";
        break;
    case 'student':
        $sql = "SELECT s.*, p.name as parentName 
                FROM student s 
                LEFT JOIN parent p ON s.parentID = p.parentID";
        break;
    case 'assessment':
        $sql = "SELECT a.*, t.name as teacherName 
                FROM assessment a 
                LEFT JOIN teacher t ON a.teacherID = t.teacherID";
        break;
    case 'result':
        $sql = "SELECT r.*, s.name as studentName, a.description as assessmentName 
                FROM result r 
                LEFT JOIN student s ON r.studentID = s.studentID 
                LEFT JOIN assessment a ON r.assessmentID = a.assessmentID";
        break;
    case 'announcement':
        $sql = "SELECT a.*, t.name as teacherName 
                FROM announcement a 
                LEFT JOIN teacher t ON a.teacherID = t.teacherID";
        break;
}

$sql .= " ORDER BY " . $columns[$entity][0];
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View <?php echo ucfirst($entity); ?></title>
    <style>
        body {
            font-family: Trebuchet MS, sans-serif;
            text-align: left;
            margin: 0;
            background-color: #f4f4f4;
            overflow-x: hidden;
        }
        .view-container {
            width: 60%;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            justify-content: left;
            align-items: left;
            flex-grow: 1;
            height: 80vh;
            margin-left: 450px;
            overflow: auto;
        }
        
        .view-title {
            margin: 20px 0px 20px 50px;
        }

        .view-table-container {
            margin: 0 50px;
            overflow-x: auto;
        }

        .view-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .view-th, .view-td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .view-th {
            background-color: #557FF7;
            color: white;
            position: sticky;
            top: 0;
        }

        .view-tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .view-no-records {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .view-actions {
            text-align: center;
            white-space: nowrap;
        }

        .view-actions a {
            text-decoration: none;
            font-size: 13px;
            color: black;
            padding: 0 3px;
        }

        .view-actions a:hover {
            color: #557FF7;
        }

        .score-cell {
            font-weight: bold;
        }

        .score-pass {
            color: hsl(134, 61.80%, 40.00%);
        }

        .score-fail {
            color: red;
        }

        .status-cell {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-verified {
            color:hsl(134, 61.80%, 40.00%);
        }

        .status-unverified {
            color:rgb(255, 169, 31);
        }
    </style>
</head>
<body>
    <div class="view-container">
        <h1 class="view-title"><?php echo ucfirst($entity); ?> List</h1>
        
        <div class="view-table-container">
            <table class="view-table">
                <thead>
                    <tr class="view-tr">
                        <?php foreach($columns[$entity] as $index => $column): ?>
                            <th class="view-th">
                                <?php echo $columns_names[$entity][$index] ?? ucfirst($column); ?>
                            </th>
                        <?php endforeach; ?>
                        <th class="view-th"></th> <!-- Actions column -->
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($result) > 0): // mysqli_num_rows execute the query // SELECT return result obj, UPDATE INSERT DELETE return true or false
                        while($row = mysqli_fetch_assoc($result)): // mysqli_fetch_assoc retrieve actual data
                    ?>
                        <tr class="view-tr">
                            <?php foreach($columns[$entity] as $column): ?>
                                <td class="view-td <?php 
                                    if($column === 'finalScore') echo 'score-cell ' . ($row[$column] >= 50 ? 'score-pass' : 'score-fail');
                                    if($column === 'status') echo 'status-cell status-' . strtolower($row[$column]);
                                ?>">
                                    <?php
                                    switch($column) { // handle special cases (foreign key references -- use id output name, formatting %, consist line breaks)
                                        case 'teacherID':
                                            echo htmlspecialchars($row['teacherName'] ?? $row[$column]); // converts special characters (<, >, &, ") into safe HTML entities
                                            break;
                                        case 'parentID':
                                            echo htmlspecialchars($row['parentName'] ?? $row[$column]); // if dh name, use id
                                            break;
                                        case 'studentID':
                                            if(isset($row['studentName'])) {
                                                echo htmlspecialchars($row['studentName']);
                                            } else {
                                                echo htmlspecialchars($row[$column]);
                                            }
                                            break;
                                        case 'assessmentID':
                                            if(isset($row['assessmentName'])) { // check exists (not null)
                                                echo htmlspecialchars($row['assessmentName']); // retreive from atas
                                            } else {
                                                echo htmlspecialchars($row[$column]);
                                            }
                                            break;
                                        case 'finalScore':
                                            echo htmlspecialchars($row[$column]) . '%';
                                            break;
                                        case 'details':
                                            echo nl2br(htmlspecialchars($row[$column])); // newline to <br> break
                                            break;
                                        default:
                                            echo htmlspecialchars($row[$column]);
                                    }
                                    ?>
                                </td>
                            <?php endforeach; ?>
                            <td class="view-td view-actions">
                                <a href="editSetting.php?entity=<?php echo $entity; ?>&id=<?php echo $row[$columns[$entity][0]]; ?>">✏️</a>
                                <a href="deleteSetting.php?entity=<?php echo $entity; ?>&pk=<?php echo $row[$columns[$entity][0]]; ?>" 
                                   onclick="return confirm('Are you sure you want to delete this <?php echo $entity; ?>?')">🗑️</a>
                            </td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr class="view-tr">
                            <td colspan="<?php echo count($columns[$entity]) + 1; ?>" class="view-td view-no-records">
                                No <?php echo $entity; ?> records found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>