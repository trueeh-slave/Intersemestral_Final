<?php
    function generatePassword($length, $options)
    {
        $characters = '';
        if (in_array('uppercase', $options)) {
            $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if (in_array('lowercase', $options)) {
            $characters .= 'abcdefghijklmnopqrstuvwxyz';
        }
        if (in_array('numbers', $options)) {
            $characters .= '0123456789';
        }
        if (in_array('symbols', $options)) {
            $characters .= '!@#$%^&*()-_=+[]{}|;:",.<>?';
        }

        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }

        echo "<p> .$password.</p>";

        return $password;
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $length = isset($_POST['length']) ? intval($_POST['length']) : 12;
        $options = isset($_POST['options']) ? $_POST['options'] : [];

        $password = generatePassword($length, $options);

        echo $password;
}

