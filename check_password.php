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
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <title>Resultado de Fortaleza de Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
            max-height: 90vh;
            overflow-y: auto;
        }
        h1 {
            color: #333;
        }
        p, ul {
            text-align: left;
            color: #555;
        }
        li {
            margin-bottom: 10px;
        }
        .back-button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007BFF;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
        .password-generator {
            margin-top: 20px;
            text-align: left;
        }
        .password-generator label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        .password-generator input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .password-generator .checkbox-group {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }
        .password-generator .checkbox-group label {
            font-weight: normal;
            display: flex;
            align-items: center;
        }
        .password-generator .btn {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        .password-generator .btn:hover {
            background-color: #0056b3;
        }
        .password-generator .password-display {
            margin-top: 10px;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
        }

        .btn-generate {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

    </style>
</head>
<body>
<div class="container">
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <h1>Resultado de Fortaleza de Contraseña</h1>
        <ul>
            <li>Contraseña Ingresada: <?php echo htmlspecialchars($password, ENT_QUOTES, 'UTF-8'); ?></li>
            <li>Tiempo para decifrar: <?php echo htmlspecialchars($crack_time, ENT_QUOTES, 'UTF-8'); ?></li>
        </ul>
        <h2>Tipos de Caracteres en la Contraseña</h2>
        <ul>
            <li>Mayúsculas: <?php echo $character_types['uppercase'] ? 'Sí' : 'No'; ?></li>
            <li>Minúsculas: <?php echo $character_types['lowercase'] ? 'Sí' : 'No'; ?></li>
            <li>Números: <?php echo $character_types['number'] ? 'Sí' : 'No'; ?></li>
            <li>Caracteres Especiales: <?php echo $character_types['special'] ? 'Sí' : 'No'; ?></li>
        </ul>
        <!-- ======= Frequenty Asked Questions Section ======= -->
        <section class="faq">
            <div class="section-title">
                <h2>¿Tu contraseña no es segura? Te mostramos algunos tips</h2>
            </div>

            <ul class="faq-list">

                <li>
                    <a data-bs-toggle="collapse" class="collapsed" data-bs-target="#faq1">¿Qué es una contraseña segura? <i class="bx bx-down-arrow-alt icon-show"></i><i class="bx bx-x icon-close"></i></a>
                    <div id="faq1" class="collapse" data-bs-parent=".faq-list">
                        <p>
                            Es aquella que resulta difícil de adivinar. Su robustez será medida en cuánto tiempo o cuántos intentos llevará descubrirla.
                            Las contraseñas largas y complejas o las frases de contraseña que mezclan grupos de palabras son muy robustas, las contraseñas cortas, genéricas y fáciles de recordar son débiles.
                        </p>
                    </div>
                </li>

                <li>
                    <a data-bs-toggle="collapse" data-bs-target="#faq2" class="collapsed">¿Cómo hacer que mi contraseña sea más segura? <i class="bx bx-down-arrow-alt icon-show"></i><i class="bx bx-x icon-close"></i></a>
                    <div id="faq2" class="collapse" data-bs-parent=".faq-list">
                        <p>
                            Las mejores contraseñas son aquellas que usan al menos 15 caracteres, emplean palabras o frases complejas de adivinar (que no se relacionen entre sí), además cuentan con una mezcla de letras mayúsculas y minúsculas, al igual que números y símbolos siempre y cuando estos no sigan una serie consecutiva.
                        </p>
                    </div>
                </li>
            </ul>
        </section>

        <section class="password-generator">
            <label for="length">Longitud de la contraseña:</label>
            <input type="number" id="length" name="length" value="12" min="1" max="20">
            <div class="checkbox-group">
                <label for="uppercase"><input type="checkbox" id="uppercase" name="options[]" value="uppercase" checked> Mayúsculas</label>
                <label for="lowercase"><input type="checkbox" id="lowercase" name="options[]" value="lowercase" checked> Minúsculas</label>
            </div>
            <div class="checkbox-group">
                <label for="numbers"><input type="checkbox" id="numbers" name="options[]" value="numbers" checked> Números</label>
                <label for="symbols"><input type="checkbox" id="symbols" name="options[]" value="symbols" checked> Símbolos</label>
            </div>
            <form id='passwordForm' action="getNewTime.php" method="post">
                <input type="hidden" id="generatedPassword" name="password">
                <div class="btn-generate">
                    <button type="button" class="back-button" onclick="generatePasswordAndCopy()">Generar y Copiar Contraseña</button>
                </div>
            </form>
            <br>
            <div class="password-display" id="password">************</div>
            <div class="password-display" id="crackTime">  </div>
        </section
        <br>
        <br>
        <br>
        <a href="index.html" class="back-button">Volver</a>
    <?php endif; ?>
</div>
<script>

    function generatePasswordAndCopy() {
        const length = document.getElementById('length').value;
        const options = {
            uppercase: document.getElementById('uppercase').checked,
            lowercase: document.getElementById('lowercase').checked,
            numbers: document.getElementById('numbers').checked,
            symbols: document.getElementById('symbols').checked
        };

        if (length < 5) {
            alert('La longitud mínima de la contraseña es de 5 caracteres');
            return;
        } else if (length > 32) {
            alert('La longitud máxima de la contraseña es 32 caracteres');
            return;
        }

        const password = generatePassword(length, options);
        document.getElementById('password').innerText = password;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'getNewTime.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                document.getElementById('crackTime').innerText = response.crack_time;
            }
        };
        xhr.send('password=' + encodeURIComponent(password));

        copyToClipboard(password);
    }


    function generatePassword(length, options) {
        const uppercaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';
        const numberChars = '0123456789';
        const symbolChars = '!@#$%^&*()_+~`|}{[]:;?><,./-=';
        let allChars = '';
        if (options.uppercase) allChars += uppercaseChars;
        if (options.lowercase) allChars += lowercaseChars;
        if (options.numbers) allChars += numberChars;
        if (options.symbols) allChars += symbolChars;

        let password = '';
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * allChars.length);
            password += allChars[randomIndex];
        }
        return password;
    }


    function copyToClipboard(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        alert("Contraseña copiada al portapapeles");
    }
</script>
</body>
</html>
