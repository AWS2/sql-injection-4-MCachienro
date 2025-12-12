<html>
<head>
    <title>Registrar usuari</title>
    <style>
        .msg { padding: 8px; background: lightyellow; margin-top: 10px; }
        .user { padding: 8px; background: lightgreen; margin-top: 10px; }
    </style>
</head>

<body>
<h1>Registrar usuari</h1>

<?php

if( isset($_POST["user"]) && isset($_POST["password"]) ) {

    $dbhost = $_ENV["DB_HOST"];
    $dbname = $_ENV["DB_NAME"];
    $dbuser = $_ENV["DB_USER"];
    $dbpass = $_ENV["DB_PASSWORD"];

    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);

    $username = $_POST["user"];
    $pass = $_POST["password"];

    # SHA2 (512) igual que el login
    $qstr = "INSERT INTO users (name, password, role)
             VALUES ('$username', SHA2('$pass',512), 'user');";

    $consulta = $pdo->prepare($qstr);

    echo "<br>$qstr<br>";

    $consulta->execute();

    if( $consulta->errorInfo()[1] ) {
        echo "<p class='msg'>ERROR: ".$consulta->errorInfo()[2]."</p>";
        die;
    }

    # MENSAJE EXACTO QUE PIDE EL TEST
    echo "<div class='user'>Usuari $username creat correctament.</div>";
}
?>

<fieldset>
    <legend>Formulari de registre</legend>
    <form method="post">
        Usuari: <input type="text" name="user" /><br><br>
        Contrasenya: <input type="password" name="password" /><br><br>

        <!-- El test espera EXACTAMENTE value="Registre" -->
        <input type="submit" value="Registre" />
    </form>
</fieldset>

</body>
</html>
