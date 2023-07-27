<?php
require_once 'vendor/autoload.php';

session_start();

// Koneksi ke database (Ganti dengan informasi koneksi database Anda)
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'php-jwt';

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Periksa apakah username ada di database
    $check_query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($check_query);
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Jika password cocok, buatkan token JWT
            $secret_key = 'paijo';
            $payload = array(
                'user_id' => $row['id'],
                'username' => $row['username']
            );

            // Buat token JWT menggunakan library Firebase\JWT\JWT
            $token = \Firebase\JWT\JWT::encode($payload, $secret_key, 'HS512', null, ['kid' => 'my_key_1']);

            // Simpan token dalam session
            $_SESSION['jwt_token'] = $token;

            // Redirect ke halaman index.php
            header("Location: index.php");
            exit();
        }
    }

    echo "Login gagal.";
}
?>

<!-- Form Login -->
<form action="login.php" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    <br>
    <input type="submit" value="Login">
</form>
