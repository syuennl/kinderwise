<?php
// editUser.php - Complete file
session_start();
include("../connection.php");
include("sidebar.php");

// Validate role and id parameters
if (!isset($_GET['role']) || !isset($_GET['ID'])) {
    echo "<script>alert('Role and ID parameters are required'); window.location.href='admin.php';</script>";
    exit();
}

$role = $_GET['role'];
$ID = $_GET['ID'];

// Validate role value
$valid_roles = ['administrator', 'principal', 'teacher', 'parent'];
if (!in_array($role, $valid_roles)) {
    echo "<script>alert('Invalid role specified'); window.location.href='admin.php';</script>";
    exit();
}

// Get available classes for teachers
$classes = [];
if ($role == 'teacher') {
    $classQuery = "SELECT classCode FROM class WHERE teacherId IS NULL OR teacherId = ? ORDER BY classCode";
    $stmt = mysqli_prepare($conn, $classQuery);
    mysqli_stmt_bind_param($stmt, "i", $ID);
    mysqli_stmt_execute($stmt);
    $classResult = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($classResult)) {
        $classes[] = $row['classCode'];
    }
}

// Fetch existing user data
$stmt = mysqli_prepare($conn, "SELECT * FROM $role WHERE " . $role . "ID = ?");
mysqli_stmt_bind_param($stmt, "i", $ID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "<script>alert('User not found'); window.location.href='admin.php';</script>";
    exit();
}

if (isset($_POST['submit'])) {
    $isValid = true;
    $errorMessage = "";

    // Your existing validation code here...

    if ($isValid) {
        mysqli_begin_transaction($conn);
        
        try {
            // Sanitize inputs
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $contactNumber = mysqli_real_escape_string($conn, $_POST['contactNumber']);
            
            // Build SQL query
            $sql = "UPDATE $role SET name = ?, email = ?, contactNumber = ?";
            $params = [$name, $email, $contactNumber];
            $types = "sss";

            // Add password to update if provided
            if (!empty($_POST['password'])) {
                $password = mysqli_real_escape_string($conn, $_POST['password']);
                $sql .= ", password = ?";
                $params[] = $password;
                $types .= "s";
            }

            // Handle teacher class assignment
            if ($role == 'teacher') {
                $classAssigned = $_POST['classAssigned'] === '' ? null : mysqli_real_escape_string($conn, $_POST['classAssigned']);
                $sql .= ", classAssigned = ?";
                $params[] = $classAssigned;
                $types .= "s";

                if ($classAssigned !== $user['classAssigned']) {
                    // Check if new class exists and is available
                    $checkClassQuery = "SELECT classCode FROM class WHERE classCode = ? AND (teacherId IS NULL OR teacherId = ?)";
                    $stmtCheck = mysqli_prepare($conn, $checkClassQuery);
                    mysqli_stmt_bind_param($stmtCheck, "si", $classAssigned, $ID);
                    mysqli_stmt_execute($stmtCheck);
                    $result = mysqli_stmt_get_result($stmtCheck);
                    
                    if (mysqli_num_rows($result) === 0) {
                        throw new Exception("Selected class is not available");
                    }

                    // Remove teacher from old class
                    $updateOldClassSql = "UPDATE class SET teacherId = NULL WHERE teacherId = ?";
                    $stmtOldClass = mysqli_prepare($conn, $updateOldClassSql);
                    mysqli_stmt_bind_param($stmtOldClass, "i", $ID);
                    mysqli_stmt_execute($stmtOldClass);

                    // Assign teacher to new class
                    if ($classAssigned) {
                        $updateNewClassSql = "UPDATE class SET teacherId = ? WHERE classCode = ?";
                        $stmtNewClass = mysqli_prepare($conn, $updateNewClassSql);
                        mysqli_stmt_bind_param($stmtNewClass, "is", $ID, $classAssigned);
                        mysqli_stmt_execute($stmtNewClass);
                    }
                }
            }

            $sql .= " WHERE " . $role . "ID = ?";
            $params[] = $ID;
            $types .= "i";

            // Prepare and execute statement
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            mysqli_stmt_execute($stmt);

            mysqli_commit($conn);
            echo "<script>alert('User updated successfully!'); window.location.href='viewUser.php?role=" . $role . "';</script>";
            exit();
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
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
    <title>Edit <?php echo ucfirst($role); ?></title>
    <link href="container.css" rel="stylesheet">
    <script>
        function validateForm() {
            const form = document.getElementById('editUserForm');
            const submitBtn = document.querySelector('.submit-btn');
            const email = form.email.value;
            const password = form.password.value;
            const name = form.name.value;
            const contactNumber = form.contactNumber.value;
            let isValid = true;

            // Reset validations
            form.email.classList.remove('valid', 'invalid');
            form.password.classList.remove('valid', 'invalid');
            form.name.classList.remove('valid', 'invalid');
            form.contactNumber.classList.remove('valid', 'invalid');

            // Validate name
            if (name.length > 50 || name.length === 0) {
                form.name.classList.add('invalid');
                isValid = false;
            } else {
                form.name.classList.add('valid');
            }

            // Validate email
            if (!email.endsWith('@kinderwise.com') || email.length > 100 || email.length === 0) {
                form.email.classList.add('invalid');
                isValid = false;
            } else {
                form.email.classList.add('valid');
            }

            // Validate contact number
            if (!contactNumber.match(/^[0-9]+$/) || contactNumber.length > 11 || contactNumber.length === 0) {
                form.contactNumber.classList.add('invalid');
                isValid = false;
            } else {
                form.contactNumber.classList.add('valid');
            }

            // Validate password only if it's not empty
            if (password.length > 0) {
                if (password.length > 8) {
                    form.password.classList.add('invalid');
                    isValid = false;
                } else {
                    form.password.classList.add('valid');
                }
            }

            // Enable/disable submit button
            submitBtn.disabled = !isValid;

            return isValid;
        }

        function goBack() {
            window.location.href = 'viewUser.php?role=<?php echo $role; ?>';
        }

        function validateNumber(event) {
            const charCode = (event.which) ? event.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                event.preventDefault();
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <div class="container">
        <h1>Edit <?php echo ucfirst($role); ?></h1>

        <form method="POST" id="editUserForm" onsubmit="return validateForm()">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" required oninput="validateForm()" maxlength="50" 
                       value="<?php echo htmlspecialchars($user['name']); ?>">
                <div class="hint">Maximum 50 characters</div>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required oninput="validateForm()" maxlength="100"
                       value="<?php echo htmlspecialchars($user['email']); ?>">
                <div class="hint">Must end with @kinderwise.com (max 100 characters)</div>
            </div>

            <div class="form-group">
                <label>Contact Number:</label>
                <input type="text" name="contactNumber" required oninput="validateForm()"
                    onkeypress="return validateNumber(event)" maxlength="11"
                    value="<?php echo htmlspecialchars($user['contactNumber']); ?>">
                <div class="hint">Numbers only (max 11 digits)</div>
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" maxlength="8" oninput="validateForm()">
                <div class="hint">Leave blank to keep current password (Maximum 8 characters)</div>
            </div>

            <?php if ($role == 'teacher'): ?>
                <div class="form-group">
                    <label>Class Assigned:</label>
                    <select name="classAssigned" >
                        <option value="">NULL</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo htmlspecialchars($class); ?>"
                                <?php echo ($user['classAssigned'] == $class) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($class); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="button-group">
                <button type="submit" name="submit" class="submit-btn" >Update</button>
                <button type="button" class="cancel-btn" onclick="goBack()">Cancel</button>
            </div>
        </form>
    </div>
</body>

</html>