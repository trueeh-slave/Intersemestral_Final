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

function checkCharacterTypes($password) {
    $containsUppercase = preg_match('/[A-Z]/', $password);
    $containsLowercase = preg_match('/[a-z]/', $password);
    $containsNumber = preg_match('/[0-9]/', $password);
    $containsSpecial = preg_match('/[\W_]/', $password);

    return [
        'uppercase' => $containsUppercase,
        'lowercase' => $containsLowercase,
        'number' => $containsNumber,
        'special' => $containsSpecial
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $crack_time = getCrackTimeEstimate($password);
    $character_types = checkCharacterTypes($password);

    echo "<h1>Tiempo Estimado para Descifrar la Contraseña</h1>";
    echo "<p>Tiempo para descifrar '$password': $crack_time</p>";

    echo "<h1>Tipos de Caracteres en la Contraseña</h1>";
    echo "<ul>";
    echo "<li>Mayúsculas: " . ($character_types['uppercase'] ? 'Sí' : 'No') . "</li>";
    echo "<li>Minúsculas: " . ($character_types['lowercase'] ? 'Sí' : 'No') . "</li>";
    echo "<li>Números: " . ($character_types['number'] ? 'Sí' : 'No') . "</li>";
    echo "<li>Caracteres Especiales: " . ($character_types['special'] ? 'Sí' : 'No') . "</li>";
    echo "</ul>";
}
?>
