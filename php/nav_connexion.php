<?php

/* Récupère l'état de connexion :
   0 = déconnecté
   1 = connecté, admin
   2 = connecté, user */
function getConnectState()
{
    global $utilisateur, $link;
    if (empty($_SESSION)) {
        $connectState = 0;
    } elseif (checkIfAdmin($_SESSION["user"], $link)) {
        //si c'est un admin
        $connectState = 1;
        $utilisateur = $_SESSION["user"];
    } else {
        //si c'est un user
        $connectState = 2;
        $utilisateur = $_SESSION["user"];
    }

    return $connectState;
}

/* Renvoie un temps plus lisible au format h-min-sec à partir d'un temps en secondes */
function timeElapsed($secs)
{
    $bit = array(
    'h' => $secs / 3600 % 24,
    'min' => $secs / 60 % 60,
    'sec' => $secs % 60
    );

    foreach ($bit as $k => $v) {
        if ($v > 0) {
            $ret[] = $v . $k;
        }
    }

    if (empty($bit['h']) && empty($bit['min']) && empty($bit['sec'])) {
        return "0sec";
    } else {
        return join(' ', $ret);
    }
}

/* Affiche le bon rôle et le bon lien de profil en fonction de qui est connecté */
function profilButton($connectState, $utilisateur, $role)
{
    if ($connectState == 2) {
        echo 'Bonjour <a href="./profilUtilisateur.php">' . $utilisateur . '</a> <role>' . $role . '</role> ';
    } else {
        echo 'Bonjour <a href="./profilAdmin.php">' . $utilisateur . '</a> <role>' . $role . '</role> ';
    }
}

/* Affiche ce que renvoie profilButton() et la durée de connexion */
function showUser($connectState, $utilisateur, $link)
{
    global $role;
    if (($connectState == 1) || ($connectState == 2)) {
        profilButton($connectState, $utilisateur, $role);

        if (isset($_SESSION["logged"])) {
            $time = time() - $_SESSION["logged"];
            $readableTime = timeElapsed($time);
            echo "<i>(connecté depuis " . $readableTime . ")</i>";
        }
    } else {
        echo "";
    }
}

/* Affiche les bons boutons de navigation selon l'état de connexion */
function connectButton($connectState)
{
    if ($connectState == 0) {
        echo '<a class="boutonNav" href="./connexion.php">se connecter</a> <a class="boutonInscrip" href="./inscription.php">s\'inscrire</a>';
    } elseif (($connectState == 1) || ($connectState == 2)) {
        echo '<a class="boutonNav" href="./ajouter.php">ajouter une image</a> <form action="index.php" method="POST"><input class="boutonNav" type="submit" name="deconnexion" value="Se déconnecter"></form>';
    }
}
