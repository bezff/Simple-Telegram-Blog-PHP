<?php
require_once __DIR__ . '/core/functions.php';

//Получаем входящие данные
$content = file_get_contents("php://input");
$update = json_decode($content, true);

//Если пришел пустой запрос
if (!$update || !isset($update['message'])) die('Silence is gold');

$message = $update['message'];
$chat_id = $message['chat']['id'];
$user_id = $message['from']['id'];

//Проверка авторизации
if ($user_id != ADMIN_ID) {
    sendMessage($chat_id, "Доступ запрещен. Этот бот управляется администратором сайта.");
    exit;
}

//Текст сообщения или подпись к фото
$text = $message['text'] ?? $message['caption'] ?? '';

//Обработка команды /start
if ($text === '/start') {
    sendMessage($chat_id, "Привет!\n\n<b>Как постить:</b>\n1. Отправь текст -> получится текстовый пост.\n2. Отправь фото (с подписью или без) -> получится пост с картинкой.\n\nЖду контент!");
    exit;
}

//Обработка фото
if (isset($message['photo'])) {
    //Берем самое последнее фото в массиве
    $photoArray = end($message['photo']);
    $file_id = $photoArray['file_id'];
    
    $savedImageName = downloadTelegramFile($file_id);
    
    if ($savedImageName) {
        savePost($text, $savedImageName);
        sendMessage($chat_id, "Фото-пост опубликован!");
    } else {
        sendMessage($chat_id, "Ошибка при скачивании картинки.");
    }
    exit;
}

//Обработка простого текста
if (!empty($text)) {
    savePost($text);
    sendMessage($chat_id, "Текстовый пост опубликован!");
    exit;
}

//Неизвестный формат
sendMessage($chat_id, "Я понимаю только текст или фото.");