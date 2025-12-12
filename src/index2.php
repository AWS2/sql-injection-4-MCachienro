<html>
 <head>
    <title>SQL injection</title>
    <style>
        .user {
            background-color: yellow;
        }
    </style>
 </head>

 <body>
    <h1>PDO vulnerable a SQL injection</h1>

<?php
if (isset($_POST["user"])) {

    $dbhost = $_ENV["DB_HOST"];
    $dbname = $_ENV["DB_NAME"];
    $dbuser = $_ENV["DB_USER"];
    $dbpass = $_ENV["DB_PASSWORD"];

    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $username = $_POST["user"];
    $pass     = $_POST["password"];

    // Query segura
    $qstr = "SELECT * FROM users 
             WHERE name = :username 
               AND password = SHA2(:pass, 512)";

    $consulta = $pdo->prepare($qstr);

    // Bind real (els tests comproven que no hi ha SQLi)
    $consulta->bindParam(":username", $username, PDO::PARAM_STR);
    $consulta->bindParam(":pass", $pass, PDO::PARAM_STR);

    try {
        $consulta->execute();
    } catch (PDOException $e) {
        echo "<p>ERROR: " . $e->getMessage() . "</p>";
        die;
    }

    if ($consulta->rowCount() >= 1) {
        foreach ($consulta as $user) {
            echo "<div class='user'>Hola " . htmlspecialchars($user["name"]) .
                 " (" . htmlspecialchars($user["role"]) . ").</div>";
        }
    } else {
        echo "<div class='user'>No hi ha cap usuari amb aquest nom i contrasenya.</div>";
    }
}
?>

    <fieldset>
        <legend>Login form</legend>
        <form method="post">
            User: <input type="text" name="user"><br>
            Pass: <input type="text" name="password"><br>
            <input type="submit" value="Login"><br>
        </form>
    </fieldset>

 </body>
</html>
