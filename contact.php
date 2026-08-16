<?php
// تفعيل الجلسات لتذكر وقت إرسال آخر رسالة
session_start();

$host = "localhost";
$username = "root";
$password = "";
$dbname = "portfolio_db";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // الأمان 1: وضع الإنتاج (Production Mode) - لا تعرض أخطاء قواعد البيانات أبداً للزوار
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT); 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // الأمان 2: مصيدة البوتات (Honeypot) - إذا كان الحقل المخفي ممتلئاً، فهو بوت خبيث!
        if (!empty($_POST['bot_trap'])) {
            die("Security Alert: Bot detected. Connection Terminated.");
        }

        // الأمان 3: مكافحة السبام (Anti-Spam) - يمنع إرسال أكثر من رسالة واحدة كل دقيقتين
        if (isset($_SESSION['last_message_time'])) {
            $time_passed = time() - $_SESSION['last_message_time'];
            if ($time_passed < 120) { // 120 ثانية = دقيقتين
                die("Please wait before sending another message. Anti-Spam active.");
            }
        }

        // تنظيف البيانات
        $name = htmlspecialchars(strip_tags($_POST['name']));
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $subject = "No Subject"; // تم الاستغناء عنها في الواجهة
        $message = htmlspecialchars(strip_tags($_POST['message']));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format");
        }

        $sql = "INSERT INTO messages (name, email, subject, message) VALUES (:name, :email, :subject, :message)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);

        if ($stmt->execute()) {
            // تحديث وقت إرسال آخر رسالة في الجلسة
            $_SESSION['last_message_time'] = time();
            header("Location: index.html?status=success#contact");
            exit();
        } else {
            echo "Error processing your request. Try again later.";
        }
    }

} catch(PDOException $e) {
    // تم إخفاء الخطأ الحقيقي لمنع كشف بنية قاعدة البيانات
    echo "A server error occurred. Our team has been notified.";
}
?>