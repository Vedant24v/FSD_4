<?php
// 1. DATABASE CONNECTION (Internal db.php)
$conn = new mysqli("localhost", "root", "", "studentdb");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// 2. DELETE LOGIC
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM student WHERE id=$id");
    header("Location: index.php");
}

// 3. INSERT OR UPDATE LOGIC
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $department = $_POST['department'];
    $id = $_POST['id'];

    if ($id != "") {
        // UPDATE existing record
        $conn->query("UPDATE student SET name='$name', email='$email', mobile='$mobile', department='$department' WHERE id=$id");
    } else {
        // INSERT new record
        $conn->query("INSERT INTO student (name, email, mobile, department) VALUES ('$name', '$email', '$mobile', '$department')");
    }
    header("Location: index.php");
}

// 4. FETCH DATA FOR EDITING
$edit_name = $edit_email = $edit_mobile = $edit_dept = $edit_id = "";
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = $conn->query("SELECT * FROM student WHERE id=$id");
    $row = $res->fetch_assoc();
    $edit_id = $row['id'];
    $edit_name = $row['name'];
    $edit_email = $row['email'];
    $edit_mobile = $row['mobile'];
    $edit_dept = $row['department'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All-in-One Student CRUD</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 30px; background: #f4f7f6; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input { padding: 10px; margin: 5px; width: 45%; border: 1px solid #ddd; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #eee; text-align: left; }
        th { background: #f04e30; color: white; }
        .btn { padding: 10px 20px; background: #333; color: white; border: none; cursor: pointer; border-radius: 4px; }
        .btn:hover { background: #f04e30; }
    </style>
</head>
<body>

<div class="container">
    <h2>Student Management (Single-Page CRUD)</h2>
    
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
        <input type="text" name="name" placeholder="Full Name" value="<?php echo $edit_name; ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?php echo $edit_email; ?>" required>
        <input type="text" name="mobile" placeholder="Mobile" value="<?php echo $edit_mobile; ?>" required>
        <input type="text" name="department" placeholder="Department" value="<?php echo $edit_dept; ?>" required>
        <br>
        <button type="submit" name="save" class="btn">
            <?php echo ($edit_id != "") ? "Update Student" : "Add Student"; ?>
        </button>
        <?php if($edit_id != ""): ?> <a href="index.php">Cancel</a> <?php endif; ?>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Department</th>
            <th>Actions</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM student");
        while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['department']; ?></td>
            <td>
                <a href="index.php?edit=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="index.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>