<?php
require_once __DIR__ . '/../config.php';


//Отправка сообщения в Telegram
function sendMessage($chat_id, $text) {
    $url = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage";
    $data = [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];
    
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    $context  = stream_context_create($options);
    file_get_contents($url, false, $context);
}


//Скачивание изображения из Telegram
function downloadTelegramFile($file_id) {
    //Получаем путь к файлу
    $apiUrl = "https://api.telegram.org/bot" . BOT_TOKEN . "/getFile?file_id=" . $file_id;
    $response = json_decode(file_get_contents($apiUrl), true);
    
    if (!isset($response['result']['file_path'])) return null;
    
    $remoteFilePath = $response['result']['file_path'];
    $downloadUrl = "https://api.telegram.org/file/bot" . BOT_TOKEN . "/" . $remoteFilePath;
    
    //Генерируем имя файла и сохраняем
    $extension = pathinfo($remoteFilePath, PATHINFO_EXTENSION);
    $fileName = uniqid() . '.' . $extension;
    $localPath = __DIR__ . '/../assets/uploads/' . $fileName;
    
    file_put_contents($localPath, file_get_contents($downloadUrl));
    
    return $fileName;
}


//Сохранение поста в JSON
function savePost($text, $image = null) {
    $posts = [];
    if (file_exists(DB_FILE)) {
        $posts = json_decode(file_get_contents(DB_FILE), true);
        if (!is_array($posts)) $posts = [];
    }
    
    //Добавляем новый пост в начало массива
    array_unshift($posts, [
        'id' => time(),
        'date' => date('d.m.Y H:i'),
        'text' => $text,
        'image' => $image
    ]);
    
    file_put_contents(DB_FILE, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}


//Получение всех постов
function getPosts() {
    if (!file_exists(DB_FILE)) return [];
    $data = json_decode(file_get_contents(DB_FILE), true);
    return is_array($data) ? $data : [];
}