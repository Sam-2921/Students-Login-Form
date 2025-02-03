<?php
$servername = "localhost";
$username = "samson";
$password = "samson";
$dbname = "registration_db";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $admission_id = $conn->real_escape_string($_POST['admission_id']);
    $name = $conn->real_escape_string($_POST['name']);

    if ($admission_id == '1234567890' && $name == 'Admin') {
        $_SESSION['admin'] = true;
        $_SESSION['admission_id'] = null; 
        header("Location: " . $_SERVER['PHP_SELF'] . "?action=registration");
        exit();
    }

    $sql = "SELECT * FROM users WHERE admission_id = '$admission_id' AND first_name = '$name'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['admission_id'] = $admission_id;
        $_SESSION['admin'] = false;
        header("Location: " . $_SERVER['PHP_SELF'] . "?action=registration");
        exit();
    } else {
        $login_error = "Invalid Admission ID or Name";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $admission_id = $conn->real_escape_string($_POST['admission_id']);
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $contact_details = $conn->real_escape_string($_POST['contact_details']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $address = $conn->real_escape_string($_POST['address']);

    $sql = "SELECT * FROM users WHERE admission_id = '$admission_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $signup_error = "Admission ID already exists.";
    } else {
        $sql = "INSERT INTO users (admission_id, first_name, last_name, contact_details, gender, address) 
                VALUES ('$admission_id', '$first_name', '$last_name', '$contact_details', '$gender', '$address')";
        if ($conn->query($sql) === TRUE) {
            $signup_success = "Sign up successful. <a href='?action=login'>Login here</a>";
        } else {
            $signup_error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

if ((isset($_SESSION['admission_id']) || isset($_SESSION['admin'])) && $_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['submit']) && !isset($_POST['alter'])) {
        $admission_id = $conn->real_escape_string($_POST['admission_id']);
        $first_name = $conn->real_escape_string($_POST['first_name']);
        $last_name = $conn->real_escape_string($_POST['last_name']);
        $contact_details = $conn->real_escape_string($_POST['contact_details']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $address = $conn->real_escape_string($_POST['address']);

        $sql = "INSERT INTO users (admission_id, first_name, last_name, contact_details, gender, address) 
                VALUES ('$admission_id', '$first_name', '$last_name', '$contact_details', '$gender', '$address')";
        if ($conn->query($sql) === TRUE) {
            $registration_message = "New record created successfully";
        } else {
            $registration_error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if (isset($_POST['alter'])) {
        $id = intval($_POST['id']);
        $admission_id = $conn->real_escape_string($_POST['admission_id']);
        $first_name = $conn->real_escape_string($_POST['first_name']);
        $last_name = $conn->real_escape_string($_POST['last_name']);
        $contact_details = $conn->real_escape_string($_POST['contact_details']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $address = $conn->real_escape_string($_POST['address']);

        $sql = "UPDATE users SET admission_id='$admission_id', first_name='$first_name', last_name='$last_name', contact_details='$contact_details', gender='$gender', address='$address' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            $update_message = "Record updated successfully";
        } else {
            $update_error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $sql = "UPDATE users SET deleted_at = NOW() WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        $delete_message = "Record marked as deleted successfully";
    } else {
        $delete_error = "Error: " . $sql . "<br>" . $conn->error;
    }
}

if (isset($_SESSION['admin']) && $_SESSION['admin']) {
    $user_condition = "WHERE deleted_at IS NULL";
} elseif (isset($_SESSION['admission_id'])) {
    $user_condition = "WHERE admission_id = '{$_SESSION['admission_id']}' AND deleted_at IS NULL";
} else {
    $user_condition = "WHERE 1=0"; 
}

$result = $conn->query("SELECT * FROM users $user_condition");

if (isset($_GET['alter_id'])) {
    $alter_id = intval($_GET['alter_id']);
    $alter_result = $conn->query("SELECT * FROM users WHERE id = $alter_id");
    if ($alter_result->num_rows == 1) {
        $alter_row = $alter_result->fetch_assoc();
    } else {
        $alter_error = "Record not found.";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF'] . "?action=login");
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : 'login';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($action); ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            background: url('bg_pic.jpg') no-repeat center center fixed;
            background-size: cover;
            background-position: center;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 8px;   
            max-width: 800px;
            margin-top: 50px;
        }
        .container h1, .container h2, .form-group, .table {
            color: white; 
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($action == 'login'): ?>
            <h1 class="mb-4">Login</h1>
            <?php if (isset($login_error)) echo "<div class='alert alert-danger'>$login_error</div>"; ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="admission_id">Admission ID:</label>
                    <input type="text" id="admission_id" name="admission_id" class="form-control" placeholder="Enter Your Admission Id" required>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter Your First Name" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary">Login</button>
                <a href="?action=signup" class="btn btn-link">Create an account</a>
            </form>

        <?php elseif ($action == 'signup'): ?>
            <h1 class="mb-4">Sign Up</h1>
            <?php if (isset($signup_success)) echo "<div class='alert alert-success'>$signup_success</div>"; ?>
            <?php if (isset($signup_error)) echo "<div class='alert alert-danger'>$signup_error</div>"; ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="admission_id">Admission ID:</label>
                    <input type="text" id="admission_id" name="admission_id" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="contact_details">Contact Details:</label>
                    <input type="text" id="contact_details" name="contact_details" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" class="form-control" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" name="signup" class="btn btn-primary">Sign Up</button>
                <a href="?action=login" class="btn btn-link">Already have an account? Login</a>
            </form>

        <?php elseif ($action == 'registration' && (isset($_SESSION['admission_id']) || isset($_SESSION['admin']))): ?>
            <a href="?logout" class="btn btn-outline-danger mb-3">Logout</a>

            <h1 class="mb-4">Registration Form</h1>
            <?php if (isset($registration_message)) echo "<div class='alert alert-success'>$registration_message</div>"; ?>
            <?php if (isset($registration_error)) echo "<div class='alert alert-danger'>$registration_error</div>"; ?>
            <?php if (isset($update_message)) echo "<div class='alert alert-success'>$update_message</div>"; ?>
            <?php if (isset($update_error)) echo "<div class='alert alert-danger'>$update_error</div>"; ?>
            <?php if (isset($alter_error)) echo "<div class='alert alert-danger'>$alter_error</div>"; ?>
            <?php if (isset($delete_message)) echo "<div class='alert alert-success'>$delete_message</div>"; ?>
            <?php if (isset($delete_error)) echo "<div class='alert alert-danger'>$delete_error</div>"; ?>

            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo isset($alter_row['id']) ? intval($alter_row['id']) : ''; ?>">
                <div class="form-group">
                    <label for="admission_id">Admission ID:</label>
                    <input type="text" id="admission_id" name="admission_id" class="form-control" value="<?php echo isset($alter_row['admission_id']) ? htmlspecialchars($alter_row['admission_id']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo isset($alter_row['first_name']) ? htmlspecialchars($alter_row['first_name']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo isset($alter_row['last_name']) ? htmlspecialchars($alter_row['last_name']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="contact_details">Contact Details:</label>
                    <input type="text" id="contact_details" name="contact_details" class="form-control" value="<?php echo isset($alter_row['contact_details']) ? htmlspecialchars($alter_row['contact_details']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" class="form-control" required>
                        <option value="Male" <?php echo isset($alter_row['gender']) && $alter_row['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo isset($alter_row['gender']) && $alter_row['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo isset($alter_row['gender']) && $alter_row['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" class="form-control" rows="4" required><?php echo isset($alter_row['address']) ? htmlspecialchars($alter_row['address']) : ''; ?></textarea>
                </div>
                <button type="submit" name="submit" class="btn btn-outline-success">Submit</button>
                <?php if (isset($alter_row)): ?>
                    <button type="submit" name="alter" class="btn btn-outline-warning">Alter</button>
                <?php endif; ?>
            </form>

            <h2 class="mt-5">Records</h2>
            <table class="table table-bordered mt-3">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Admission ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Contact Details</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['admission_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['contact_details']); ?></td>
                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td>
                            <a href="?action=registration&alter_id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-outline-warning btn-sm">Alter</a></br>
                            <a href="?delete_id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-outline-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Please <a href="?action=login">login</a> to access the registration form.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
