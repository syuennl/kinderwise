<?php
session_start();
include("../connection.php");
include("sidebar.php");

// Validate entity and ID parameters
if (!isset($_GET['entity']) || !isset($_GET['id'])) {
    echo "<script>alert('Entity and ID parameters are required'); window.location.href='admin.php';</script>";
    exit();
}

$entity = $_GET['entity'];
$id = $_GET['id'];

$valid_entities = ['year', 'semester', 'subject', 'class', 'student', 'assessment', 'result', 'announcement'];
if (!in_array($entity, $valid_entities)) {
    echo "<script>alert('Invalid entity specified'); window.location.href='admin.php';</script>";
    exit();
}

// Define columns and their display names (same as viewSetting.php)
$columns = [
    'year' => ['yearCode', 'description'],
    'semester' => ['semesterCode', 'yearCode', 'startDate', 'endDate'],
    'subject' => ['subjectName', 'yearCode'],
    'class' => ['classCode', 'teacherID', 'yearCode', 'classCapacity'],
    'student' => ['studentID', 'name', 'age', 'birthday', 'gender', 'parentID', 'classCode', 'yearCode'],
    'assessment' => ['assessmentID', 'assessmentType', 'teacherID', 'subjectName', 'semesterCode', 'yearCode', 'description', 'deadline', 'status'],
    'result' => ['resultID', 'finalScore', 'studentID', 'assessmentID'],
    'announcement' => ['announcementID', 'announcementTitle', 'details', 'postDate', 'teacherID']
];

$columns_names = [
    'year' => ['Year Code', 'Description'],
    'semester' => ['Semester Code', 'Year Code', 'Start Date', 'End Date'],
    'subject' => ['Subject Name', 'Year Code'],
    'class' => ['Class Code', 'Teacher', 'Year Code', 'Capacity'],
    'student' => ['ID', 'Name', 'Age', 'Birthday', 'Gender', 'Parent', 'Class Code', 'Year Code'],
    'assessment' => ['ID', 'Type', 'Teacher', 'Subject Name', 'Semester Code', 'Year Code', 'Description', 'Deadline', 'Status'],
    'result' => ['ID', 'Score', 'Student', 'Assessment'],
    'announcement' => ['ID', 'Title', 'Details', 'Post Date', 'Teacher']
];

function getInputTypes($entity) {
    $input_types = [];
    
    switch($entity) {
        case 'year':
            $input_types = [
                'yearCode' => ['type' => 'text', 'maxlength' => 20],
                'description' => ['type' => 'text', 'maxlength' => 50]
            ];
            break;
            
        case 'semester':
            $input_types = [
                'semesterCode' => ['type' => 'text', 'maxlength' => 20],
                'yearCode' => ['type' => 'select', 'source' => 'years'],
                'startDate' => ['type' => 'date'],
                'endDate' => ['type' => 'date']
            ];
            break;
            
        case 'subject':
            $input_types = [
                'subjectName' => ['type' => 'text', 'maxlength' => 50],
                'yearCode' => ['type' => 'select', 'source' => 'years']
            ];
            break;
            
        case 'class':
            $input_types = [
                'classCode' => ['type' => 'text', 'maxlength' => 20],
                'teacherID' => ['type' => 'select', 'source' => 'teachers'],
                'yearCode' => ['type' => 'select', 'source' => 'years'],
                'classCapacity' => ['type' => 'number', 'min' => 1, 'max' => 15]
            ];
            break;
            
        case 'student':
            $input_types = [
                'studentID' => ['type' => 'hidden'],
                'name' => ['type' => 'text', 'maxlength' => 100],
                'age' => ['type' => 'number', 'min' => 1],
                'birthday' => ['type' => 'date'],
                'gender' => ['type' => 'select', 'options' => ['Male', 'Female']],
                'parentID' => ['type' => 'select', 'source' => 'parents'],
                'classCode' => ['type' => 'select', 'source' => 'classs'],
                'yearCode' => ['type' => 'select', 'source' => 'years']
            ];
            break;
            
        case 'assessment':
            $input_types = [
                'assessmentID' => ['type' => 'hidden'],
                'assessmentType' => ['type' => 'select', 'options' => ['Midterm', 'Finals']],
                'teacherID' => ['type' => 'select', 'source' => 'teachers'],
                'subjectName' => ['type' => 'select', 'source' => 'subjects'],
                'semesterCode' => ['type' => 'select', 'source' => 'semesters'],
                'yearCode' => ['type' => 'select', 'source' => 'years'],
                'description' => ['type' => 'text', 'maxlength' => 50],
                'deadline' => ['type' => 'date'],
                'status' => ['type' => 'select', 'options' => ['no submission', 'submitted']]
            ];
            break;
            
        case 'result':
            $input_types = [
                'resultID' => ['type' => 'hidden'],
                'finalScore' => ['type' => 'number', 'min' => 0, 'max' => 100, 'step' => 0.1],
                'studentID' => ['type' => 'select', 'source' => 'students'],
                'assessmentID' => ['type' => 'select', 'source' => 'assessments']
            ];
            break;
            
        case 'announcement':
            $input_types = [
                'announcementID' => ['type' => 'hidden'],
                'announcementTitle' => ['type' => 'text', 'maxlength' => 100],
                'details' => ['type' => 'textarea', 'maxlength' => 300],
                'postDate' => ['type' => 'date'],
                'teacherID' => ['type' => 'select', 'source' => 'teachers'],
                'status' => ['type' => 'select', 'options' => ['unverified', 'verified']]
            ];
            break;
    }
    
    return $input_types;
}

$input_types = getInputTypes($entity);


// Define validation rules for each field type
$validation_rules = [
    'yearCode' => [
        'pattern' => '^[A-Za-z0-9]{1,20}$',
        'message' => 'Year code must be alphanumeric and not exceed 20 characters'
    ],
    'description' => [
        'pattern' => '^.{1,50}$',
        'message' => 'Description must not exceed 50 characters'
    ],
    'semesterCode' => [
        'pattern' => '^[A-Za-z0-9]{1,20}$',
        'message' => 'Semester code must be alphanumeric and not exceed 20 characters'
    ],
    'subjectName' => [
        'pattern' => '^[A-Za-z0-9\s]{1,50}$',
        'message' => 'Subject name must be alphanumeric and not exceed 50 characters'
    ],
    'classCode' => [
        'pattern' => '^[A-Za-z0-9]{1,20}$',
        'message' => 'Class code must be alphanumeric and not exceed 20 characters'
    ],
    'classCapacity' => [
        'min' => 1,
        'max' => 15,
        'message' => 'Class capacity must be between 1 and 15'
    ],
    'name' => [
        'pattern' => '^[A-Za-z\s]{1,100}$',
        'message' => 'Name must contain only letters and spaces, not exceeding 100 characters'
    ],
    'age' => [
        'min' => 1,
        'max' => 150,
        'message' => 'Age must be between 1 and 150'
    ],
    'finalScore' => [
        'min' => 0,
        'max' => 100,
        'message' => 'Score must be between 0 and 100'
    ],
    'announcementTitle' => [
        'pattern' => '^.{1,100}$',
        'message' => 'Title must not exceed 100 characters'
    ],
    'details' => [
        'pattern' => '^.{1,500}$',
        'message' => 'Details must not exceed 500 characters'
    ]
];

// Server-side validation function
function validateField($field, $value, $validation_rules) {
    if (!isset($validation_rules[$field])) {
        return true; // No validation rules for this field
    }

    $rules = $validation_rules[$field];

    // Pattern validation
    if (isset($rules['pattern'])) {
        if (!preg_match('/' . $rules['pattern'] . '/', $value)) {
            return $rules['message'];
        }
    }

    // Min/Max validation for numbers
    if (isset($rules['min']) && isset($rules['max'])) {
        $num_value = floatval($value);
        if ($num_value < $rules['min'] || $num_value > $rules['max']) {
            return $rules['message'];
        }
    }

    // Date validation
    if (isset($rules['date'])) {
        $date = DateTime::createFromFormat('Y-m-d', $value);
        if (!$date || $date->format('Y-m-d') !== $value) {
            return 'Invalid date format';
        }
    }

    return true;
}

// Get available options for foreign keys
function getAvailableOptions($conn, $entity) {
    $options = [];
    
    switch ($entity) {
        case 'semester':
        case 'subject':
            $options['years'] = [];
            $query = "SELECT yearCode FROM year";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['years'][] = $row['yearCode'];
            }
            break;
            
        case 'student':
            $options['classes'] = [];
            $query = "SELECT classCode FROM class WHERE (SELECT COUNT(*) FROM student s WHERE s.classCode = class.classCode) < 15";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['classes'][] = $row['classCode'];
            }
            
            $options['parents'] = [];
            $query = "SELECT parentID, name FROM parent ORDER BY name";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['parents'][] = $row;
            }
            
            $options['years'] = [];
            $query = "SELECT yearCode FROM year";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['years'][] = $row['yearCode'];
            }
            break;
            
        case 'class':
            $options['teachers'] = [];
            $query = "SELECT t.teacherID, t.name 
                     FROM teacher t 
                     LEFT JOIN class c ON t.teacherID = c.teacherID 
                     WHERE c.teacherID IS NULL OR c.classCode = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $_GET['id']);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['teachers'][] = $row;
            }
            
            $options['years'] = [];
            $query = "SELECT yearCode FROM year";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['years'][] = $row['yearCode'];
            }
            break;
            
        case 'assessment':
            $options['teachers'] = [];
            $query = "SELECT teacherID, name FROM teacher";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['teachers'][] = $row;
            }
            
            $options['subjects'] = [];
            $query = "SELECT subjectName FROM subject";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['subjects'][] = $row['subjectName'];
            }
            
            $options['semesters'] = [];
            $query = "SELECT semesterCode FROM semester";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['semesters'][] = $row['semesterCode'];
            }
            
            $options['years'] = [];
            $query = "SELECT yearCode FROM year";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['years'][] = $row['yearCode'];
            }
            break;
            
        case 'result':
            $options['students'] = [];
            $query = "SELECT studentID, name FROM student ORDER BY name";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['students'][] = $row;
            }
            
            $options['assessments'] = [];
            $query = "SELECT assessmentID, description FROM assessment ORDER BY description";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $options['assessments'][] = $row;
            }
            break;
            
        case 'announcement':
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

// Add this function before the $current_data line

function getCurrentData($conn, $entity, $id) {
    $data = null;
    
    switch ($entity) {
        case 'year':
            $query = "SELECT * FROM year WHERE yearCode = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $id);
            break;
            
        case 'semester':
            $query = "SELECT * FROM semester WHERE semesterCode = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $id);
            break;
            
        case 'subject':
            $query = "SELECT * FROM subject WHERE subjectName = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $id);
            break;
            
        case 'class':
            $query = "SELECT * FROM class WHERE classCode = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $id);
            break;
            
        case 'student':
            $query = "SELECT * FROM student WHERE studentID = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            break;
            
        case 'assessment':
            $query = "SELECT * FROM assessment WHERE assessmentID = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            break;
            
        case 'result':
            $query = "SELECT * FROM result WHERE resultID = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            break;
            
        case 'announcement':
            $query = "SELECT * FROM announcement WHERE announcementID = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            break;
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    
    if (!$data) {
        echo "<script>alert('Record not found'); window.location.href='admin.php';</script>";
        exit();
    }
    
    return $data;
}

// Get available options and current data (reuse existing functions)
$available_options = getAvailableOptions($conn, $entity);
$current_data = getCurrentData($conn, $entity, $id);

if (isset($_POST['submit'])) {
    $isValid = true;
    $errorMessages = [];
    $updateFields = []; // Initialize the array
    $types = "";
    $values = [];
    
    // Validate each submitted field
    foreach ($columns[$entity] as $column) {
        // Skip primary key
        if ($column === $columns[$entity][0]) continue;
        
        if (isset($_POST[$column])) {
            $validation_result = validateField($column, $_POST[$column], $validation_rules);
            if ($validation_result !== true) {
                $isValid = false;
                $errorMessages[] = $validation_result;
            }

            // Add to update fields even if validation fails - we'll only use them if validation passes
            $updateFields[] = "$column = ?";
            
            // Determine parameter type
            if (strpos($column, 'ID') !== false || $column === 'age' || $column === 'classCapacity') {
                $types .= "i"; // integer
            } elseif (strpos($column, 'Score') !== false) {
                $types .= "d"; // double/float
            } else {
                $types .= "s"; // string
            }
            $values[] = $_POST[$column];
        }
    }

    if ($isValid && !empty($updateFields)) {  // Check that we have fields to update
        // Add the WHERE clause parameter
        $types .= strpos($columns[$entity][0], 'ID') !== false ? "i" : "s";
        $values[] = $id;
        
        $sql = "UPDATE $entity SET " . implode(", ", $updateFields) . " WHERE {$columns[$entity][0]} = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            // Bind parameters dynamically
            $bindParams = array($types);
            foreach ($values as $key => $value) {
                $bindParams[] = &$values[$key];
            }
            call_user_func_array(array($stmt, 'bind_param'), $bindParams);
            
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('" . ucfirst($entity) . " updated successfully!'); window.location.href='viewSetting.php?entity=$entity';</script>";
                exit();
            } else {
                echo "<script>alert('Error updating " . ucfirst($entity) . ": " . mysqli_error($conn) . "');</script>";
            }
        } else {
            echo "<script>alert('Error preparing update statement: " . mysqli_error($conn) . "');</script>";
        }
    } else if (!empty($errorMessages)) {
        echo "<script>alert('" . implode("\\n", $errorMessages) . "');</script>";
    } else {
        echo "<script>alert('No fields to update');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit <?php echo ucfirst($entity); ?></title>
    <link href="container.css" rel="stylesheet">
    <script>
        // Client-side validation
        const validationRules = <?php echo json_encode($validation_rules); ?>;
        
        function validateForm() {
            const form = document.getElementById('editSettingForm');
            const errorMessages = [];
            
            // Clear previous error messages
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            
            // Validate each field
            for (const field in validationRules) {
                const input = form.elements[field];
                if (!input) continue;
                
                const rules = validationRules[field];
                const value = input.value.trim();

                // Pattern validation
                if (rules.pattern) {
                    const regex = new RegExp(rules.pattern);
                    if (!regex.test(value)) {
                        showError(input, rules.message);
                        errorMessages.push(rules.message);
                    }
                }
                
                // Min/Max validation for numbers
                if (rules.min !== undefined && rules.max !== undefined) {
                    const numValue = parseFloat(value);
                    if (isNaN(numValue) || numValue < rules.min || numValue > rules.max) {
                        showError(input, rules.message);
                        errorMessages.push(rules.message);
                    }
                }
                
                // Required field validation
                if (input.required && !value) {
                    showError(input, `${field} is required`);
                    errorMessages.push(`${field} is required`);
                }
            }
            
            // Special validation for dates
            const startDate = form.elements['startDate'];
            const endDate = form.elements['endDate'];
            if (startDate && endDate) {
                if (new Date(startDate.value) > new Date(endDate.value)) {
                    showError(endDate, 'End date must be after start date');
                    errorMessages.push('End date must be after start date');
                }
            }
            
            if (errorMessages.length > 0) {
                return false;
            }
            return true;
        }
        
        function showError(input, message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.style.color = 'red';
            errorDiv.style.fontSize = '12px';
            errorDiv.style.marginTop = '5px';
            errorDiv.textContent = message;
            input.parentNode.appendChild(errorDiv);
        }
        
        // Real-time validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editSettingForm');
            const inputs = form.querySelectorAll('input, select, textarea');
            
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    // Clear previous error for this input
                    const previousError = input.parentNode.querySelector('.error-message');
                    if (previousError) {
                        previousError.remove();
                    }
                    
                    // Validate this input
                    const rules = validationRules[input.name];
                    if (rules) {
                        const value = input.value.trim();
                        
                        if (rules.pattern) {
                            const regex = new RegExp(rules.pattern);
                            if (!regex.test(value)) {
                                showError(input, rules.message);
                            }
                        }
                        
                        if (rules.min !== undefined && rules.max !== undefined) {
                            const numValue = parseFloat(value);
                            if (isNaN(numValue) || numValue < rules.min || numValue > rules.max) {
                                showError(input, rules.message);
                            }
                        }
                    }
                });
            });
        });
    </script>
    <style>
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }
        
        input:invalid,
        select:invalid,
        textarea:invalid {
            border-color: #ff6b6b;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit <?php echo ucfirst($entity); ?></h1>
        
        <form method="POST" id="editSettingForm" onsubmit="return validateForm()">
            <?php foreach ($columns[$entity] as $index => $column): ?>
                <div class="form-group">
                    <?php
                    $input_type = $input_types[$column];
                    switch ($input_type['type']):
                        case 'hidden': ?>
                            <input type="hidden" 
                                name="<?php echo $column; ?>"
                                value="<?php echo htmlspecialchars($current_data[$column]); ?>">
                            <?php break;
                            
                        case 'select':
                            if (isset($input_type['source'])): // Handle foreign key selections
                                $options = $available_options[$input_type['source']] ?? [];

                                ?>
                                <label><?php echo $columns_names[$entity][$index]; ?>:</label>
                                <select name="<?php echo $column; ?>" required>
                                    <option value="">Select <?php echo $columns_names[$entity][$index]; ?></option>
                                    <?php foreach ($options as $option): 
                                        if (is_array($option)) {
                                            $value = $option[array_key_first($option)]; // First key (usually ID)
                                            $display = $option['name'] ?? $option[array_key_last($option)]; // Name or last key
                                        } else {
                                            $value = $display = $option;
                                        }
                                    ?>
                                        <option value="<?php echo htmlspecialchars($value); ?>"
                                            <?php echo ($current_data[$column] == $value) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($display); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: // Handle fixed option selections (like gender, status) ?>
                                <label><?php echo $columns_names[$entity][$index]; ?>:</label>
                                <select name="<?php echo $column; ?>" required>
                                    <option value="">Select <?php echo $columns_names[$entity][$index]; ?></option>
                                    <?php foreach ($input_type['options'] as $option): ?>
                                        <option value="<?php echo htmlspecialchars($option); ?>"
                                            <?php echo ($current_data[$column] == $option) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($option); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif;
                            break;
                        
                        case 'textarea': ?>
                            <label><?php echo $columns_names[$entity][$index]; ?>:</label>
                            <textarea name="<?php echo $column; ?>" required
                                maxlength="<?php echo $input_type['maxlength']; ?>"
                                style="width: 80%; margin: 0px 50px; height: 100px;"><?php
                                echo htmlspecialchars($current_data[$column]); 
                            ?></textarea>
                            <?php break;
                        
                        default: ?>
                            <label><?php echo $columns_names[$entity][$index]; ?>:</label>
                            <input type="<?php echo $input_type['type']; ?>"
                                name="<?php echo $column; ?>"
                                value="<?php echo htmlspecialchars($current_data[$column]); ?>"
                                required
                                <?php foreach ($input_type as $attr => $value):
                                    if ($attr !== 'type'): ?>
                                        <?php echo $attr; ?>="<?php echo $value; ?>"
                                    <?php endif;
                                endforeach; ?>>
                    <?php endswitch; ?>
                    
                    <?php if (isset($input_type['maxlength'])): ?>
                        <div class="hint">Maximum <?php echo $input_type['maxlength']; ?> characters</div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <div class="button-group">
                <button type="submit" name="submit" class="submit-btn">Update</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='viewSetting.php?entity=<?php echo $entity?>'">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>