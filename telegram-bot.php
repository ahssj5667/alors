<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$telegramToken = '7854414810:AAGyuC8sZ-zHLOdNHl81GScSPYsCOesNok8';
$website = 'https://api.telegram.org/bot'.$telegramToken;

$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);

$chatId = $update['message']['chat']['id'];
$message = $update['message']['text'];

if ($message == '/start') {
    $response = "مرحباً بك في بوت نظام التصويت!";
    sendMessage($chatId, $response);
} elseif ($message == '/stats') {
    if (isAdmin($chatId)) {
        $stats = getVotingStats();
        $response = "إحصائيات التصويت:\n";
        $response .= "إجمالي المصوتين: ".$stats['total_voters']."\n";
        $response .= "المرشح الأول: ".$stats['candidate1_votes']."\n";
        $response .= "المرشح الثاني: ".$stats['candidate2_votes'];
        sendMessage($chatId, $response);
    } else {
        sendMessage($chatId, "ليس لديك صلاحية الوصول لهذه المعلومة");
    }
}

function sendMessage($chatId, $message) {
    global $website;
    $url = $website.'/sendMessage?chat_id='.$chatId.'&text='.urlencode($message);
    file_get_contents($url);
}
?>
