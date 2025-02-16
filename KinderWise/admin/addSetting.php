<?php
session_start();
include("../connection.php");
include("sidebar.php");

// Validate entity parameter
if (!isset($_GET['entity'])) {
    echo "<script>alert('Entity parameter is missing'); window.location.href='admin.php';</script>";
    exit();
}

$entity = $_GET['entity'];
$valid_entities = ['year', 'semester', 'subject', 'class', 'student', 'assessment', 'result', 'announcement'];
if (!in_array($entity, $valid_entities)) {
    echo "<script>alert('Invalid entity specified'); window.location.href='admin.php';</script>";
    exit();
}

// Get available options for foreign keys
function getAvailableOptions($conn, $entity) {
    $options = [];
    
    switch ($entity) {
        case 'semester':
        case 'subject':
            // Get available years
            $options['years'] = [];
            $query = "SELECT yearCode FROM year";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['years'][] = $row['yearCode'];
            }
            break;

        case 'student':
            // Get available classes
            $options['classes'] = [];
            $query = "SELECT classCode FROM class";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['classes'][] = $row['classCode'];
            }
            
            // Get available parents
            $options['parents'] = [];
            $query = "SELECT parentID, name FROM parent ORDER BY name";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['parents'][] = $row;
            }
            
            // Get available years
            $options['years'] = [];
            $query = "SELECT yearCode FROM year";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['years'][] = $row['yearCode'];
            }
            break;
            
        case 'class':
            // Get available teachers (who don't have a class assigned)
            $options['teachers'] = [];
            $query = "SELECT teacherID, name
                     FROM teacher
                     WHERE classAssigned IS NULL";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['teachers'][] = $row;
            }
            
            // Get available years (yearCode)
            $options['years'] = [];
            $query = "SELECT yearCode FROM year";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['years'][] = $row['yearCode'];
            }
            break;
            
        case 'assessment':
            // Get available teachers (teacherID, name)
            $options['teachers'] = [];
            $query = "SELECT teacherID, name FROM teacher";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['teachers'][] = $row;
            }
            
            // Get available subjects (subjectName)
            $options['subjects'] = [];
            $query = "SELECT subjectName FROM subject";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['subjects'][] = $row['subjectName'];
            }
            
            // Get available semesters (semesterCode)
            $options['semesters'] = [];
            $query = "SELECT semesterCode FROM semester";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['semesters'][] = $row['semesterCode'];
            }

            // Get available years (yearCode)
            $options['years'] = [];
            $query = "SELECT yearCode FROM year";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['years'][] = $row['yearCode'];
            }
            break;
        
        case 'result':
            // Get available students (studentID, name)
            $options['students'] = [];
            $query = "SELECT studentID, name FROM student ORDER BY name";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['students'][] = $row;
            }
            
            // Get available assessments (assessmentID, description)
            $options['assessments'] = [];
            $query = "SELECT assessmentID, description FROM assessment ORDER BY description";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['assessments'][] = $row;
            }
            break;

        case 'announcement':
            // Get available teachers (teacherID, name)
            $options['teachers'] = [];
            $query = "SELECT teacherID, name FROM teacher";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['teachers'][] = $row;
            }
            break;
    }
    
    return $options;
}

$available_options = getAvailableOptions($conn, $entity);

if (isset($_POST['submit'])) {
    $isValid = true;
    $errorMessage = "";
    
    // Validate based on entity type
    switch ($entity) {
        case 'year':
            // Validate year specific fields
            if (strlen($_POST['yearCode']) > 20) {
                $isValid = false;
                $errorMessage = "Year code must not exceed 20 characters";
            }
            if (strlen($_POST['description']) > 50) {
                $isValid = false;
                $errorMessage = "Description must not exceed 50 characters";
            }
            break;

        case 'semester':
            // Validate semester specific fields
            if (strlen($_POST['semesterCode']) > 20) {
                $isValid = false;
                $errorMessage = "Semester code must not exceed 20 characters";
            }
            if ($_POST['startDate'] >= $_POST['endDate']) {
                $isValid = false;
                $errorMessage = "End date must be after start date";
            }
            break;

        case 'subject':
            // Validate subject specific fields
            if (strlen($_POST['subjectName']) > 50) {
                $isValid = false;
                $errorMessage = "Subject name must not exceed 50 characters";
            }
            break;
            
        case 'class':
            // Validate class specific fields
            if (!is_numeric($_POST['classCapacity']) || $_POST['classCapacity'] > 15) {
                $isValid = false;
                $errorMessage = "Class capacity must not exceed 15";
            }
            // Check if teacher already assigned
            if (isset($_POST['teacherID'])) {
                $query = "SELECT COUNT(*) as count FROM class WHERE teacherID = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $_POST['teacherID']);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                if ($row['count'] > 0) {
                    $isValid = false;
                    $errorMessage = "Teacher already assigned to another class";
                }
            }
            break;

        case 'student':
            // Validate student specific fields
            if (strlen($_POST['name']) > 50) {
                $isValid = false;
                $errorMessage = "Name must not exceed 50 characters";
            }
            if (!is_numeric($_POST['age']) || $_POST['age'] < 0) {
                $isValid = false;
                $errorMessage = "Invalid age";
            }
            
        case 'assessment':
            // Validate assessment specific fields
            if (strlen($_POST['description']) > 300) {
                $isValid = false;
                $errorMessage = "Description must not exceed 300 characters";
            }
            // Validate assessment doesn't exists
            $query = "SELECT COUNT(*) as count FROM assessment 
                     WHERE subjectName = ? AND semesterCode = ? AND yearCode = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sss", $_POST['subjectName'], $_POST['semesterCode'], $_POST['yearCode']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            if ($row['count'] === 1) {
                $isValid = false;
                $errorMessage = "Assessments already exists for this subject, semester and year";
            }
            break;
        
        case 'result':
            // Validate result specific fields
            if (!is_numeric($_POST['finalScore']) || $_POST['finalScore'] < 0 || $_POST['finalScore'] > 100) {
                $isValid = false;
                $errorMessage = "Invalid final score";
            }
            break;
        
        case 'announcement':
            // Validate announcement specific fields
            if (strlen($_POST['announcementTitle']) > 50) {
                $isValid = false;
                $errorMessage = "Title must not exceed 50 characters";
            }
            if (strlen($_POST['details']) > 300) {
                $isValid = false;
                $errorMessage = "Details must not exceed 300 characters";
            }
            break;
    }

    if ($isValid) {
        // Build and execute INSERT query based on entity
        switch ($entity) {
            case 'year':
                $sql = "INSERT INTO year (yearCode, description) VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", 
                    $_POST['yearCode'], 
                    $_POST['description']
                );
                break;
            
            case 'semester':
                $yearCode = ($_POST['yearCode'] === 'NULL') ? null : mysqli_real_escape_string($conn, $_POST['yearCode']);
                $sql = "INSERT INTO semester (semesterCode, yearCode, startDate, endDate) 
                        VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssss", 
                    $_POST['semesterCode'],
                    $yearCode,
                    $_POST['startDate'],
                    $_POST['endDate']
                );
                break;

            case 'subject':
                $yearCode = ($_POST['yearCode'] === 'NULL') ? null : mysqli_real_escape_string($conn, $_POST['yearCode']);
                $sql = "INSERT INTO subject (subjectName, yearCode) VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", 
                    $_POST['subjectName'],
                    $yearCode
                );
                break;
            
            case 'class':
                $teacherID = ($_POST['teacherID'] === 'NULL') ? null : mysqli_real_escape_string($conn, $_POST['teacherID']);
                $yearCode = ($_POST['yearCode'] === 'NULL') ? null : mysqli_real_escape_string($conn, $_POST['yearCode']);
                $sql = "INSERT INTO class (classCode, teacherID, yearCode, classCapacity) 
                        VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sisi", 
                    $_POST['classCode'],
                    $teacherID,
                    $yearCode,
                    $_POST['classCapacity']
                );
                break;

            case 'student':
                $parentID = ($_POST['parentID'] === 'NULL') ? null : mysqli_real_escape_string($conn, $_POST['parentID']);
                $classCode = ($_POST['classCode'] === 'NULL') ? null : mysqli_real_escape_string($conn, $_POST['classCode']);
                $yearCode = ($_POST['yearCode'] === 'NULL') ? null : mysqli_real_escape_string($conn, $_POST['yearCode']);
                $sql = "INSERT INTO student (name, age, birthday, gender, parentID, classCode, yearCode) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sissiis", 
                    $_POST['name'], 
                    $_POST['age'],
                    $_POST['birthday'],
                    $_POST['gender'],
                    $parentID,
                    $classCode,
                    $yearCode
                );
                break;
                
            case 'assessment':
                $teacherID = ($_POST['teacherID'] === 'NULL') ? null : mysqli_real_escape_string($conn, $_POST['teacherID']);
                $subjectName = ($_POST['subjectName'] === 'NULL') ? null : mysqli_real_escape_string($conn, $_POST['subjectName']);
                $semesterCode = ($_POST['semesterCode'] === 'NULL') ? null : mysqli_real_escape_string($conn, $_POST['semesterCode']);
                $yearCode = ($_POST['yearCode'] === 'NULL') ? null : mysqli_real_escape_string($conn, $_POST['yearCode']);
                $sql = "INSERT INTO assessment (assessmentType, teacherID, subjectName, 
                        semesterCode, yearCode, description, deadline, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, 'no submission')";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sisssss", 
                    $_POST['assessmentType'],
                    $teacherID,
                    $subjectName,
                    $semesterCode,
                    $yearCode,
                    $_POST['description'],
                    $_POST['deadline']
                );
                break;

            case 'result':
                $studentID = ($_POST['studentID'] === 'NULL') ? null : mysqli_real_escape_string($conn, $_POST['studentID']);
                $assessmentID = ($_POST['assessmentID'] === 'NULL') ? null : mysqli_real_escape_string($conn, $_POST['assessmentID']);
                $sql = "INSERT INTO result (finalScore, studentID, assessmentID) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "iii", 
                    $_POST['finalScore'],
                    $studentID,
                    $assessmentID
                );
                break;

            case 'announcement':
                $teacherID = ($_POST['teacherID'] === 'NULL') ? null : mysqli_real_escape_string($conn, $_POST['teacherID']);
                $sql = "INSERT INTO announcement (announcementTitle, details, postDate, teacherID) 
                        VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssi", 
                    $_POST['announcementTitle'],
                    $_POST['details'],
                    $_POST['postDate'],
                    $teacherID
                );
                break;
        }

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('" . ucfirst($entity) . " added successfully!'); window.location.href='viewSetting.php?entity=$entity';</script>";
            exit();
        } else {
            echo "<script>alert('Error adding " . ucfirst($entity) . ": " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('$errorMessage');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add <?php echo ucfirst($entity); ?></title>
    <link href="container.css" rel="stylesheet">

    <script>
        function validationForm() {
        const form = document.getElementById('addSettingForm');
        const submitBtn = document.querySelector('.submit-btn');
        const entityType = '<?php echo $entity; ?>';
        let isValid = true;

        // Reset all input validations
        form.querySelectorAll('input, select, textarea').forEach(element => {
            element.classList.remove('valid', 'invalid');
        });

        // Generic validation for required fields
        form.querySelectorAll('[required]').forEach(field => {
            if (!field.value) {
                field.classList.add('invalid');
                isValid = false;
            } else {
                field.classList.add('valid');
            }
        });

        // Entity-specific validations
        switch (entityType) {
            case 'year':
                const yearCode = form.querySelector('[name="yearCode"]');
                if (yearCode.value.length > 20) {
                    yearCode.classList.add('invalid');
                    isValid = false;
                }
                break;

            case 'student':
                const age = form.querySelector('[name="age"]');
                if (isNaN(age.value) || age.value < 0) {
                    age.classList.add('invalid');
                    isValid = false;
                }
                break;

            case 'result':
                const score = form.querySelector('[name="finalScore"]');
                if (isNaN(score.value) || score.value < 0 || score.value > 100) {
                    score.classList.add('invalid');
                    isValid = false;
                }
                break;
        }

        // Enable/disable submit button
        submitBtn.disabled = !isValid;
        return isValid;
    }
    </script>

</head>
<body>
    <div class="container">
        <h1>Add New <?php echo ucfirst($entity); ?></h1>

        <form method="POST" id="addSettingForm" onsubmit="return validationForm()">
            <?php switch($entity): 
                case 'year': ?>
                <div class="form-group">
                    <label>Year Code:</label>
                    <input type="text" name="yearCode" required maxlength="20" oninput="validationForm()">
                    <div class="hint">Maximum 20 characters</div>
                </div>

                <div class="form-group">
                    <label>Description:</label>
                    <input type="text" name="description" required maxlength="50" oninput="validationForm()">
                    <div class="hint">Maximum 50 characters</div>
                </div>

            <?php break;

                case 'semester': ?>
                <div class="form-group">
                    <label>Semester Code:</label>
                    <input type="text" name="semesterCode" required maxlength="20" oninput="validationForm()">
                    <div class="hint">Maximum 20 characters</div>
                </div>

                <div class="form-group">
                    <label>Year Code:</label>
                    <select name="yearCode" required>
                        <option value="NULL">NULL</option>
                        <?php foreach ($available_options['years'] as $year): ?>
                            <option value="<?php echo htmlspecialchars($year); ?>">
                                <?php echo htmlspecialchars($year); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Start Date:</label>
                    <input type="date" name="startDate" required oninput="validationForm()">
                </div>

                <div class="form-group">
                    <label>End Date:</label>
                    <input type="date" name="endDate" required oninput="validationForm()">
                </div>

            <?php break;

                case 'subject': ?>
                <div class="form-group">
                    <label>Subject Name:</label>
                    <input type="text" name="subjectName" required maxlength="50" oninput="validationForm()">
                    <div class="hint">Maximum 50 characters</div>
                </div>

                <div class="form-group">
                    <label>Year Code:</label>
                    <select name="yearCode" required>
                        <option value="NULL">NULL</option>
                        <?php foreach ($available_options['years'] as $year): ?>
                            <option value="<?php echo htmlspecialchars($year); ?>">
                                <?php echo htmlspecialchars($year); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
            <?php break; 

                case 'class': ?>
                <div class="form-group">
                    <label>Class Code:</label>
                    <input type="text" name="classCode" required maxlength="20" oninput="validationForm()">
                    <div class="hint">Maximum 20 characters</div>
                </div>

                <div class="form-group">
                    <label>Teacher:</label>
                    <select name="teacherID">
                        <option value="NULL">NULL</option>
                        <?php foreach ($available_options['teachers'] as $teacher): ?>
                            <option value="<?php echo $teacher['teacherID']; ?>">
                                <?php echo htmlspecialchars($teacher['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Year Code:</label>
                    <select name="yearCode" required>
                        <option value="NULL">NULL</option>
                        <?php foreach ($available_options['years'] as $year): ?>
                            <option value="<?php echo htmlspecialchars($year); ?>">
                                <?php echo htmlspecialchars($year); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Class Capacity:</label>
                    <input type="number" name="classCapacity" required min="0" max="15" oninput="validationForm()">
                    <div class="hint">Maximum 15 students</div>
                </div>
            
            <?php break;

                case 'student': ?>
                <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" required maxlength="50" oninput="validationForm()">
                <div class="hint">Maximum 50 characters</div>
                </div>

                <div class="form-group">
                <label>Age:</label>
                <input type="number" name="age" required min="0" oninput="validationForm()">
                </div>

                <div class="form-group">
                <label>Birthday:</label>
                <input type="date" name="birthday" required oninput="validationForm()">
                </div>

                <div class="form-group">
                <label>Gender:</label>
                <select name="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                </div>

                <div class="form-group">
                <label>Parent:</label>
                <select name="parentID" required>
                    <option value="NULL">NULL</option>
                    <?php foreach ($available_options['parents'] as $parent): ?>
                        <option value="<?php echo $parent['parentID']; ?>">
                            <?php echo htmlspecialchars($parent['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                </div>

                <div class="form-group">
                <label>Class Code:</label>
                <select name="classCode" required>
                    <option value="NULL">NULL</option>
                    <?php foreach ($available_options['classes'] as $class): ?>
                        <option value="<?php echo htmlspecialchars($class); ?>">
                            <?php echo htmlspecialchars($class); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                </div>

                <div class="form-group">
                <label>Year Code:</label>
                <select name="yearCode" required>
                    <option value="NULL">NULL</option>
                    <?php foreach ($available_options['years'] as $year): ?>
                        <option value="<?php echo htmlspecialchars($year); ?>">
                            <?php echo htmlspecialchars($year); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                </div>

            <?php break;

                case 'assessment': ?>
                <div class="form-group">
                    <label>Assessment Type:</label>
                    <select name="assessmentType" required>
                        <option value="Midterm">Midterm</option>
                        <option value="Finals">Finals</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Teacher:</label>
                    <select name="teacherID" required>
                        <option value="NULL">NULL</option>
                        <?php foreach ($available_options['teachers'] as $teacher): ?>
                            <option value="<?php echo $teacher['teacherID']; ?>">
                                <?php echo htmlspecialchars($teacher['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Subject Name:</label>
                    <select name="subjectName" required>
                        <option value="NULL">NULL</option>
                        <?php foreach ($available_options['subjects'] as $subject): ?>
                            <option value="<?php echo htmlspecialchars($subject); ?>">
                                <?php echo htmlspecialchars($subject); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Semester Code:</label>
                    <select name="semesterCode" required>
                        <option value="NULL">NULL</option>
                        <?php foreach ($available_options['semesters'] as $semester): ?>
                            <option value="<?php echo htmlspecialchars($semester); ?>">
                                <?php echo htmlspecialchars($semester); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                <label>Year Code:</label>
                <select name="yearCode" required>
                    <option value="NULL">NULL</option>
                    <?php foreach ($available_options['years'] as $year): ?>
                        <option value="<?php echo htmlspecialchars($year); ?>">
                            <?php echo htmlspecialchars($year); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                </div>

                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" required maxlength="300" oninput="validationForm()" 
                            style="width: 80%; margin: 0px 50px; height: 100px;"></textarea>
                    <div class="hint">Maximum 300 characters</div>
                </div>

                <div class="form-group">
                    <label>Deadline:</label>
                    <input type="date" name="deadline" required oninput="validationForm()">
                </div>

            <?php break;

                case 'result': ?>
                <div class="form-group">
                    <label>Final Score:</label>
                    <input type="number" name="finalScore" required min="0" max="100" oninput="validationForm()">
                    <div class="hint">Score between 0 and 100</div>
                </div>

                <div class="form-group">
                    <label>Student:</label>
                    <select name="studentID" required>
                        <option value="NULL">NULL</option>
                        <?php foreach ($available_options['students'] as $student): ?>
                            <option value="<?php echo $student['studentID']; ?>">
                                <?php echo htmlspecialchars($student['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Assessment:</label>
                    <select name="assessmentID" required>
                        <option value="NULL">NULL</option>
                        <?php foreach ($available_options['assessments'] as $assessment): ?>
                            <option value="<?php echo htmlspecialchars($assessment['assessmentID']); ?>">
                                <?php echo htmlspecialchars($assessment['description']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            <?php break;

                  case 'announcement': ?>
                <div class="form-group">
                    <label>Announcement Title:</label>
                    <input type="text" name="announcementTitle" required maxlength="50" oninput="validationForm()">
                    <div class="hint">Maximum 50 characters</div>
                </div>

                <div class="form-group">
                    <label>Details:</label>
                    <textarea name="details" required maxlength="300" oninput="validationForm()" 
                              style="width: 80%; margin: 0px 50px; height: 100px;"></textarea>
                    <div class="hint">Maximum 300 characters</div>
                </div>

                <div class="form-group">
                    <label>Post Date:</label>
                    <input type="date" name="postDate" required oninput="validationForm()">
                </div>

                <div class="form-group">
                    <label>Teacher:</label>
                    <select name="teacherID" required>
                        <option value="NULL">NULL</option>
                        <?php foreach ($available_options['teachers'] as $teacher): ?>
                            <option value="<?php echo $teacher['teacherID']; ?>">
                                <?php echo htmlspecialchars($teacher['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            <?php endswitch; ?>

            <div class="button-group">
                <button type="submit" name="submit" class="submit-btn">Add</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='admin.php'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>

