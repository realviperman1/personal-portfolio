<?php
session_start();
$admin_password = "243837821459";

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}

if (isset($_POST['login'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['is_admin'] = true;
    } else {
        $error = "ACCESS DENIED.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIPER SYSTEM - CLASSIFIED</title>
    <style>
        /* ستايل الهاكرز / الاستخبارات */
        :root {
            --bg-color: #050505;
            --neon-green: #0f0;
            --dark-green: #003300;
            --font-hacker: 'Courier New', Courier, monospace;
        }
        body {
            background-color: var(--bg-color);
            color: var(--neon-green);
            font-family: var(--font-hacker);
            margin: 0; padding: 40px;
        }
        ::selection { background: var(--neon-green); color: black; }
        
        .container { max-width: 1000px; margin: 0 auto; }
        
        h1, h2 { text-transform: uppercase; letter-spacing: 2px; text-shadow: 0 0 10px var(--neon-green); }
        
        /* شاشة الدخول */
        .login-box {
            border: 1px solid var(--neon-green);
            padding: 40px; text-align: center; max-width: 400px; margin: 100px auto;
            box-shadow: 0 0 20px rgba(0, 255, 0, 0.2);
            background: rgba(0, 20, 0, 0.5);
        }
        .login-box input {
            width: 100%; padding: 12px; margin: 20px 0;
            background-color: black; color: var(--neon-green);
            border: 1px solid var(--dark-green); outline: none;
            font-family: var(--font-hacker); font-size: 1.1rem;
            text-align: center;
        }
        .login-box input:focus { border-color: var(--neon-green); box-shadow: 0 0 10px var(--dark-green); }
        .btn {
            background-color: var(--neon-green); color: black;
            padding: 10px 25px; border: none; cursor: pointer;
            font-family: var(--font-hacker); font-weight: bold; font-size: 1rem;
            text-transform: uppercase; transition: 0.3s;
        }
        .btn:hover { background-color: white; box-shadow: 0 0 15px var(--neon-green); }
        .error { color: red; text-shadow: 0 0 5px red; font-weight: bold; animation: blink 1s infinite; }
        @keyframes blink { 50% { opacity: 0; } }

        /* لوحة التحكم */
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--dark-green); padding-bottom: 20px; margin-bottom: 30px; }
        .logout-btn { color: red; border: 1px solid red; padding: 5px 15px; text-decoration: none; text-transform: uppercase; transition: 0.3s; }
        .logout-btn:hover { background: red; color: black; box-shadow: 0 0 10px red; }
        
        table { width: 100%; border-collapse: collapse; border: 1px solid var(--dark-green); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid var(--dark-green); }
        th { background-color: var(--dark-green); color: var(--neon-green); text-transform: uppercase; }
        tr:hover { background-color: rgba(0, 255, 0, 0.05); }
        a { color: var(--neon-green); text-decoration: none; border-bottom: 1px dashed var(--neon-green); }
        a:hover { color: white; border-color: white; }
    </style>
</head>
<body>

<div class="container">
    <?php if (!isset($_SESSION['is_admin'])): ?>
        <div class="login-box">
            <h2>SYSTEM LOCKED</h2>
            <?php if(isset($error)) echo "<p class='error'>[!] $error</p>"; ?>
            <form method="POST">
                <input type="password" name="password" placeholder="ENTER AUTH CODE" required autofocus>
                <button type="submit" name="login" class="btn">DECRYPT</button>
            </form>
        </div>
    <?php else: ?>
        <div class="header">
            <h1>VIPER_MAN // INBOX_DATA</h1>
            <a href="?logout=true" class="logout-btn">TERMINATE_SESSION</a>
        </div>

        <table>
            <tr>
                <th>Target_Name</th>
                <th>Contact_Link</th>
                <th>Decrypted_Message</th>
                <th>Timestamp</th>
            </tr>
            <?php
            $host = "localhost"; $username = "root"; $password = ""; $dbname = "portfolio_db";
            try {
                $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                $stmt = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
                
                if ($stmt->rowCount() > 0) {
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td><a href='mailto:" . htmlspecialchars($row['email']) . "'>" . htmlspecialchars($row['email']) . "</a></td>";
                        echo "<td>" . nl2br(htmlspecialchars($row['message'])) . "</td>";
                        echo "<td>" . $row['created_at'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center;'>NO DATA FOUND.</td></tr>";
                }
            } catch(PDOException $e) {
                echo "<tr><td colspan='4'>SYSTEM ERROR: " . $e->getMessage() . "</td></tr>";
            }
            ?>
        </table>
    <?php endif; ?>
</div>

</body>
</html>