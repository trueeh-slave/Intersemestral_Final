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
    <title>Resultado de Fortaleza de Contraseña</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>
<div class="container">
    <h1>Resultado de Fortaleza de Contraseña</h1>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p><strong>Contraseña ingresada:</strong> <?php echo htmlspecialchars($password); ?></p>
        <p><strong>Tiempo para descifrar:</strong> <?php echo htmlspecialchars($crack_time); ?></p>
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

    <section>
        <div class="container">
            <div class="password" id="password">************</div>
            <form action="generatePassword.php" method="post">
                <label for="length">Longitud de la contraseña:</label>
                <input type="number" id="length" name="length" value="12" min="1" max="20">
                <br>
                <input type="checkbox" id="uppercase" name="options[]" value="uppercase" checked>
                <label for="uppercase">Mayúsculas</label>
                <input type="checkbox" id="lowercase" name="options[]" value="lowercase" checked>
                <label for="lowercase">Minúsculas</label>
                <input type="checkbox" id="numbers" name="options[]" value="numbers" checked>
                <label for="numbers">Números</label>
                <input type="checkbox" id="symbols" name="options[]" value="symbols" checked>
                <label for="symbols">Símbolos</label>
                <br><br>
                <button type="submit" class="btn">Copiar contraseña</button>

            </form>
        </div>

        <script>
            function generatePassword() {
                const length = document.getElementById('length').value;
                const options = [];
                if (document.getElementById('uppercase').checked) options.push('uppercase');
                if (document.getElementById('lowercase').checked) options.push('lowercase');
                if (document.getElementById('numbers').checked) options.push('numbers');
                if (document.getElementById('symbols').checked) options.push('symbols');

                const xhr = new XMLHttpRequest();
                xhr.open("POST", "generate.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const password = xhr.responseText;
                        document.getElementById('password').innerText = password;
                        copyToClipboard(password);
                    }
                };

                xhr.send("length=" + length + "&options=" + JSON.stringify(options));
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

    </section>
        <a href="index.html" class="back-button">Volver</a>
    <?php endif; ?>
</div>
</body>
</html>