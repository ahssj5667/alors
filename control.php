<?php
// هذا الملف يمكنك تضمينه في كل صفحاتك للتحقق من التوجيهات
session_start();

if (!isset($_SESSION['visitor_id'])) {
    $_SESSION['visitor_id'] = uniqid('visitor_', true);
}

// التحقق من وجود توجيهات لهذا الزائر
$redirects = file_exists('redirects.log') ? file('redirects.log', FILE_IGNORE_NEW_LINES) : [];
foreach ($redirects as $entry) {
    $data = json_decode($entry, true);
    if ($data['visitor_id'] === $_SESSION['visitor_id']) {
        header("Location: {$data['redirect_to']}");
        exit;
    }
}
?>
