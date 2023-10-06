<?php
ob_start();
session_start();
include('inc/header.php');

// Directly establishing the database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ims_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $type = 'user';
    $status = 1;

    // File upload logic
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);

    move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);

    $sql = "INSERT INTO ims_user (name, password, email, type, status, profile_picture) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param("ssssis", $name, $hashedPassword, $email, $type, $status, $target_file);
    $stmt->execute();

    header("Location: login.php");
}
?>


<style>
    /* Your existing CSS and additional styling */
    html,
    body,
    body>.container {
        height: 95%;
        width: 100%;
    }
    body>.container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    #title{
        text-shadow: 2px 2px 5px #000;
    }
</style>

<?php include('inc/container.php'); ?>

<h1 class="text-center my-4 py-3 text-light" id="title">Inventory Management System - PHP</h1>
<div class="col-lg-4 col-md-5 col-sm-10 col-xs-12">
    <div class="card rounded-0 shadow">
        <div class="card-header">
            <div class="card-title h3 text-center mb-0 fw-bold">Signup</div>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                    <div class="form-group">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="control-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control rounded-0" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control rounded-0" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="control-label">Password</label>
                        <input type="password" class="form-control rounded-0" id="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="mb-3">
                        <label for="profile_picture" class="control-label">Profile Picture</label>
                        <input type="file" id="profile_picture" name="profile_picture" class="form-control rounded-0" accept="image/*" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="signup" class="btn btn-primary rounded-0">Signup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('inc/footer.php'); ?>