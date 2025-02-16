<?php
session_start();
include("../connection.php");
include("sidebar.php");

// Validate role parameter
if(!isset($_GET['role'])) {
    echo "<script>alert('Role parameter is missing'); window.location.href='admin.php';</script>";
    exit();
}

$role = $_GET['role'];
// Validate role value
$valid_roles = ['administrator', 'principal', 'teacher', 'parent'];
if(!in_array($role, $valid_roles)) {
    echo "<script>alert('Invalid role specified'); window.location.href='admin.php';</script>";
    exit();
}

// Fetch users based on role
$sql = "SELECT * FROM $role ORDER BY " . $role . "ID";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View <?php echo ucfirst($role); ?>s</title>
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
    </style>
</head>
<body>
    <div class="view-container">
        <h1 class="view-title"><?php echo ucfirst($role); ?>s List</h1>
        
        <div class="view-table-container">
            <table class="view-table">
                <thead>
                    <tr class="view-tr">
                        <th class="view-th">ID</th>
                        <th class="view-th">Name</th>
                        <th class="view-th">Email</th>
                        <th class="view-th">Contact Number</th>
                        <?php if($role == 'teacher'): ?>
                            <th class="view-th">Class Assigned</th>
                        <?php endif; ?>
                        <th class="view-th"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(mysqli_num_rows($result) > 0):
                        while($row = mysqli_fetch_assoc($result)): 
                    ?>
                        <tr class="view-tr">
                            <td class="view-td"><?php echo htmlspecialchars($row[$role.'ID']); ?></td>
                            <td class="view-td"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="view-td"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="view-td"><?php echo htmlspecialchars($row['contactNumber']); ?></td>
                            <?php if($role == 'teacher'): ?>
                                <td class="view-td"><?php echo htmlspecialchars($row['classAssigned'] ?? "-"); ?></td>
                            <?php endif; ?>
                            <td class="view-td view-actions">
                                <a href="editUser.php?role=<?php echo $role; ?>&ID=<?php echo $row[$role.'ID']; ?>">✏️</a>
                                <a href="deleteUser.php?role=<?php echo $role; ?>&ID=<?php echo $row[$role.'ID']; ?>" 
                                   onclick="return confirm('Are you sure you want to delete this <?php echo $role; ?>?')">🗑️</a>
                            </td>
                        </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <tr class="view-tr">
                            <td colspan="<?php echo ($role == 'teacher' ? '6' : '5'); ?>" class="view-td view-no-records">
                                No <?php echo $role; ?>s found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>