<?php

/**
 * Получение данных из базы по пользователям
 *
 * @param string|null $user_ids
 * @return array
 */
function load_users_data(?string $user_ids) : array
{
    $user_ids = explode(',', $user_ids);
    $ret = [];
    $db = mysqli_connect("localhost", "root", null, "test");
    if (!$db) {
        echo 'Ошибка подключения: ' . mysqli_connect_error();
    }

    // Нужно подготавливать запрос, если не хотим напороться на sql инъекцию
    $sql = mysqli_prepare($db, "SELECT * FROM users WHERE id = ?");
    $sql->bind_param('i', $user_id);

    foreach ($user_ids as $user_id) {
        // Ищем пользователя
        $sql->execute();
        $user = $sql->get_result()->fetch_object();
        // если найден - записываем его в $ret
        if ($user) {
            $ret[$user->id] = $user->name;
        }
        else {
            // Тут можно делать что-то, если какой-то пользователь не найден. Зависит от требуемой логики
        }
    }

    mysqli_close($db);
    return $ret;
}

// Как правило, в $_GET['user_ids'] должна приходить строка
// с номерами пользователей через запятую, например: 1,2,17,48

$user_ids = $_GET['user_ids'];
//$user_ids = '19,2,3,4,5,6,0, 2, qwerty';
$data = load_users_data($user_ids);
foreach ($data as $user_id => $name) {
    // Оборачиваем все, что мы не контроллируем, в htmlentities, чтобы не было сюрпризов.
    // $user_id мы контроллируем, его можно оставить как есть.
    echo "<a href=\"/show_user.php?id=$user_id\">" . htmlentities($name) . "</a>";
}
