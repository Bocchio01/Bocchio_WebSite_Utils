<?php

include_once "../../_setting.php";
require_once "../../_isAdmin.php";


if ($login) {
    $host = HOST_URL;
    $users = Query("SELECT email, lang FROM BWS_Users WHERE newsletter = 1");
    // Prende il mese corrente, deve prendere il precedente
    $new_pages = Query("SELECT p.name, CONCAT('$host', t.it) as it, CONCAT('$host', t.en) as en FROM BWS_Pages AS p JOIN BWS_Translations AS t WHERE p.id_page = t.id_page AND YEAR(p.creation_date) = YEAR(CURRENT_DATE()) AND MONTH(p.creation_date) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) ORDER BY p.id_page");

    if ($new_pages->num_rows && $users->num_rows) {
        foreach ($lang as $l) $message[$l] = "<ul>";

        while ($row = $new_pages->fetch_array(MYSQLI_ASSOC)) {
            foreach ($lang as $l) $message[$l] .= "<li><a href='$row[$l]'>$row[name]</a></li>";
        }

        foreach ($lang as $l) $message[$l] = render('../template/' . $l . '/Newsletter.php', array('msg' => $message[$l] . "</ul>"));

        while ($user = $users->fetch_array(MYSQLI_ASSOC)) mail($user['email'], $subject . " - Newsletter", $message[$user['lang']], $headers);
    }
    // error_log('Error', 3, '../../log.txt');
}
