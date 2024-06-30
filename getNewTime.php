<?php
require 'vendor/autoload.php';

use ZxcvbnPhp\Zxcvbn;

function getCrackTimeEstimate($password) {
    $zxcvbn = new Zxcvbn();
    $result = $zxcvbn->passwordStrength($password);
    $crack_times_seconds = $result['crack_times_seconds'];
    $worst_case_time = $crack_times_seconds['offline_fast_hashing_1e10_per_second'];
    return convertTime($worst_case_time);
}

function convertTime($seconds) {
    if ($seconds <= 1) {
        return 'instantáneo';
    } elseif ($seconds < 60) {
        return round($seconds, 2) . ' segundos';
    } elseif ($seconds < 3600) {
        return round($seconds / 60) . ' minutos';
    } elseif ($seconds < 86400) {
        return round($seconds / 3600) . ' horas';
    } elseif ($seconds < 31536000) {
        return round($seconds / 86400) . ' días';
    } elseif ($seconds < 3153600000) {
        return round($seconds / 31536000) . ' años';
    } else {
        return round($seconds / 3153600000) . ' siglos';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $crack_time = getCrackTimeEstimate($password);

    header('Content-Type: application/json');
    echo json_encode(['crack_time' => $crack_time]);
    exit;
}