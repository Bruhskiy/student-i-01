<?php
$host = 'db';
$db_name = 'magazin';
$db_user = 'root';
$db_pas = '1234';
try {
    $db = new PDO('mysql:host=' . $host . ';dbname=' . $db_name, $db_user, $db_pas);
} catch (PDOException $e) {
    $result = '{"error": {"text": "' . $e->getMessage() . '"}}';
    die();
}
$result = '';
if (!empty($_GET['login']) && !empty($_GET['password'])) {
    $login = $_GET['login'];
    $password = $_GET['password'];
    $sql = sprintf('SELECT `ID`, `LOGIN` FROM `users` WHERE `LOGIN` LIKE \'%s\' AND `PASSW` LIKE \'%s\'', $login, $password);
    $result = '{"user":';
    $stmt = $db->query($sql)->fetch();
    if (isset($stmt['ID'])) {
        $id = $stmt['ID'];
        //creHepupyem HOBBIM TOKEH ANA nonb3oBaTeNA
        $token = md5(time());
        //I/ spemA AemcTBMA TOKeHa - 48 4aca
        $expiration = time() + 48 * 60 * 60;
        $result .= sprintf('{"id":%d, "token":"%s", "expired":%d}', $id, $token, $expiration);
        $result .= '}';
        //O6HOBMM TOKEH M BpeMA ero xu3HM B 6a3e
        $sql - sprintf("UPDATE `users` SET `TOKEN` = '%s', `EXPIRED` = FROM_UNIXTIME(%d) WHERE `ID` = %d", $token, $expiration, $id);
        $db->exec($sql);
    } else {
        $result = '{"error": {"text": "Неверный логин/пароль"}}';
    }
} else {
    $result - '{"error": {"text": "Не передан логин/пароль"}}';
}
echo $result;