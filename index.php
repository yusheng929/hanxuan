<?php
// 图片文件夹路径
$imageFolder = 'images/';

// 获取文件夹中的所有图片
$images = glob($imageFolder . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

// 请求计数文件路径
$counterFile = 'request_counter.txt';

// 增加请求计数
if (file_exists($counterFile)) {
    $count = intval(file_get_contents($counterFile));
    $count++;
} else {
    $count = 1;
}

file_put_contents($counterFile, $count);

// 开始会话或者恢复会话
session_start();

// 如果没有存储已发送图片的数组，则创建一个空数组
if (!isset($_SESSION['sent_images'])) {
    $_SESSION['sent_images'] = array();
}

// 如果所有图片都已发送过一次，则重新开始
if (empty($images)) {
    $_SESSION['sent_images'] = array();
    $images = glob($imageFolder . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
}

// 从图片列表中排除已发送的图片
$availableImages = array_diff($images, $_SESSION['sent_images']);

// 从剩余的图片中随机选择一张图片
$randomImage = $availableImages[array_rand($availableImages)];

// 将此图片添加到已发送图片的数组中
$_SESSION['sent_images'][] = $randomImage;

// 设置响应头
header('Content-Type: image/jpeg'); // 根据你的图片类型设置正确的 Content-Type

// 输出随机选择的图片内容
readfile($randomImage);
