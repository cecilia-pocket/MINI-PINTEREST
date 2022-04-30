<?php

/* Variables globales pour accéder à la BDD */
$dbHost = "localhost";
$dbUser = "p1908025";
$dbPwd = "Switch57Spinal";
$dbName = "p1908025";

/* Crée une connexion avec la BDD */
function getConnection($dbHost, $dbUser, $dbPwd, $dbName)
{
    //Crée une connexion
    $connexion = mysqli_connect($dbHost, $dbUser, $dbPwd, $dbName);

    //Vérifie la connexion
    if (!$connexion) {
        echo "Erreur lors de la connexion à la base de données : (" . mysqli_connect_errno() . ") " . mysqli_connect_error();
    }

    return $connexion;
}

/* Exécute la requête SQL de type SELECT dans la BDD et renvoie le résultat */
function executeQuery($link, $query)
{
    $resultat = mysqli_query($link, $query, MYSQLI_STORE_RESULT);

    if (!$resultat) {
        echo "Le résultat de la requête " . $query . " est faux";
    }

    return $resultat;
}

/* Exécute la requête SQL de type INSERT / UPDATE / DELETE dans la BDD et renvoie le résultat */
function executeUpdate($link, $query)
{
    $resultat = mysqli_query($link, $query);

    if (!$resultat) {
        echo "Le résultat de la requête " . $query . " est faux";
    }
}

/* Ferme la connexion active $link passée en entrée */
function closeConnexion($link)
{
    mysqli_close($link);
}
