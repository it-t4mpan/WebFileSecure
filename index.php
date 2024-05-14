<?php
session_start();

// Dummy user data for login (for demo purposes only)
$valid_username = "1718";
$valid_password = "Kud4";

// Function to log activity
function logActivity($message) {
    $logFile = 'activity.log';
    $timestamp = date('[Y-m-d H:i:s]') . ' ';
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown User Agent';
    $logMessage = $timestamp . '[' . $ipAddress . '] ' . $message . ' User-Agent: ' . $userAgent . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
}

// Function to get user IP address
function getUserIP() {
    return $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP';
}

// Function to check if file extension is allowed
function isAllowedExtension($fileName) {
    $allowedExtensions = array('png', 'jpg', 'jpeg', 'gif', 'pdf', 'txt');
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    return in_array($fileExtension, $allowedExtensions);
}

// Function to sanitize file and directory names
function sanitizeName($name) {
    return preg_replace('/[^A-Za-z0-9_\-]/', '_', $name);
}

// Check if user is trying to log in
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // Validate credentials
    if ($username == $valid_username && $password == $valid_password) {
        $_SESSION["loggedin"] = true;
        logActivity('User logged in successfully');
        header("Location: index.php");
        exit;
    } else {
        $login_err = "Invalid username or password.";
        logActivity('Failed login attempt');
    }
}

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Display login form if not logged in
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: #f5f5f5;
            }
            .login-container {
                background-color: #fff;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                width: 300px;
            }
            input[type="text"], input[type="password"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-sizing: border-box;
            }
            input[type="submit"] {
                background-color: #007bff;
                color: #fff;
                border: none;
                padding: 12px 20px;
                border-radius: 4px;
                cursor: pointer;
                width: 100%;
            }
            input[type="submit"]:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h2>Login</h2>
            <form method="post" action="">
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <input type="submit" value="Login">
            </form>
            <p style="color: red;"><?php if (isset($login_err)) { echo $login_err; } ?></p>
        </div>
    </body>
    </html>';
    logActivity('Login form displayed');
    exit;
}

// Logout user
if (isset($_GET["logout"])) {
    session_destroy();
    logActivity('User logged out');
    header("Location: index.php");
    exit;
}

// Directory where files are stored (change as per your setup)
$directory = './wfs/';

// Handle file upload
if (isset($_POST["upload"])) {
    $uploadedFile = $_FILES["file"];
    $fileName = $uploadedFile["name"];

    // Check file extension
    if (!isAllowedExtension($fileName)) {
        echo '<p style="color: red;">File extension not allowed.</p>';
        logActivity('Attempted upload of disallowed file type: ' . $fileName);
    } else {
        $sanitizedFileName = sanitizeName($fileName);
        $targetPath = $directory . basename($sanitizedFileName);
        if (move_uploaded_file($uploadedFile["tmp_name"], $targetPath)) {
            echo '<p style="color: green;">File uploaded successfully!</p>';
            logActivity('File uploaded: ' . $sanitizedFileName);
        } else {
            echo '<p style="color: red;">Failed to upload file.</p>';
            logActivity('Failed to upload file: ' . $sanitizedFileName);
        }
    }
}

// Handle directory creation
if (isset($_POST["create_directory"])) {
    $newDirectoryName = $_POST["new_directory_name"];
    $sanitizedDirectoryName = sanitizeName($newDirectoryName);
    $newDirectoryPath = $directory . $sanitizedDirectoryName;

    if (!file_exists($newDirectoryPath)) {
        if (mkdir($newDirectoryPath)) {
            echo '<p style="color: green;">Directory created successfully!</p>';
            logActivity('Directory created: ' . $sanitizedDirectoryName);
        } else {
            echo '<p style="color: red;">Failed to create directory.</p>';
            logActivity('Failed to create directory: ' . $sanitizedDirectoryName);
        }
    } else {
        echo '<p style="color: red;">Directory already exists.</p>';
        logActivity('Attempted to create existing directory: ' . $sanitizedDirectoryName);
    }
}

// Handle CRUD operations in directory listing
if (isset($_GET["action"]) && isset($_GET["name"])) {
    $action = $_GET["action"];
    $name = urldecode($_GET["name"]);
    $sanitizedName = sanitizeName($name);
    $path = $directory . $sanitizedName;

    if ($action == "delete") {
        if (file_exists($path)) {
            if (is_dir($path)) {
                // Delete directory
                $files = array_diff(scandir($path), array('.', '..'));
                foreach ($files as $file) {
                    (is_dir("$path/$file")) ? rmdir_recursive("$path/$file") : unlink("$path/$file");
                }
                if (rmdir($path)) {
                    echo '<p style="color: green;">Directory deleted successfully!</p>';
                    logActivity('Directory deleted: ' . $sanitizedName);
                } else {
                    echo '<p style="color: red;">Failed to delete directory.</p>';
                    logActivity('Failed to delete directory: ' . $sanitizedName);
                }
            } else {
                // Delete file
                if (unlink($path)) {
                    echo '<p style="color: green;">File deleted successfully!</p>';
                    logActivity('File deleted: ' . $sanitizedName);
                } else {
                    echo '<p style="color: red;">Failed to delete file.</p>';
                    logActivity('Failed to delete file: ' . $sanitizedName);
                }
            }
        } else {
            echo '<p style="color: red;">File or directory not found.</p>';
            logActivity('Attempted to delete non-existing file or directory: ' . $sanitizedName);
        }
    } elseif ($action == "download" && !is_dir($path)) {
        // Handle file download
        if (file_exists($path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($path).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            readfile($path);
            logActivity('File downloaded: ' . $sanitizedName);
            exit;
        } else {
            echo '<p style="color: red;">File not found.</p>';
            logActivity('Attempted to download non-existing file: ' . $sanitizedName);
        }
    }
}

// Recursive delete for directories
function rmdir_recursive($dir) {
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? rmdir_recursive("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

// Display file manager page if logged in
echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebFileSecure (WFS)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        .logout-btn {
            background-color: #dc3545;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .form-group input[type="text"] {
            width: 200px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>WebFileSecure (WFS)</h1>
    <a href="?logout=true" class="logout-btn">Logout</a>
    <form method="post" action="">
        <div class="form-group">
            <input type="text" name="new_directory_name" placeholder="Enter directory name" required>
            <input type="submit" name="create_directory" value="Create Directory">
        </div>
    </form>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <input type="file" name="file" required>
            <input type="submit" name="upload" value="Upload">
        </div>
    </form>
    <table>
        <tr>
            <th>Name</th>
            <th>Actions</th>
        </tr>';

// Display files and directories
$files = scandir($directory);
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        $encodedName = urlencode($file);
        echo '<tr>';
        echo '<td>' . htmlspecialchars($file) . '</td>';
        echo '<td>';
        echo '<a href="?action=delete&name=' . $encodedName . '">Delete</a>';
        if (!is_dir($directory . $file)) {
            echo ' | <a href="?action=download&name=' . $encodedName . '">Download</a>';
        }
        if (is_dir($directory . $file)) {
            echo ' | <a href="?ls=true&directory=' . $encodedName . '">List Directory</a>';
        }
        echo '</td>';
        echo '</tr>';
    }
}

echo '
    </table>
</body>
</html>';

// Log script execution completion
logActivity('Script execution completed');

?>
