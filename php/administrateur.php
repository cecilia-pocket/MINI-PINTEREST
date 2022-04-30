<?php

/* Vérifie si l'utilisateur est un admin ou pas */
function checkIfAdmin($pseudo, $link)
{
    $query = "SELECT * FROM Utilisateur WHERE pseudo = '" . $pseudo ."' AND role = 'admin'";
    $result = executeQuery($link, $query);
    return mysqli_num_rows($result) == 1;
}

/* Récupère la liste de pseudos de tous les admins */
function getAllAdmins($link)
{
    $query = "SELECT pseudo FROM Utilisateur WHERE role = 'admin';";
    $adminsList = array();
    foreach ($link->query($query) as $row) {
        $adminsList[] = $row['pseudo'];
    }
    return $adminsList;
}

/* Récupère la liste de pseudos de tous les utilisateurs normaux */
function getAllUsers($link)
{
    $query = "SELECT pseudo FROM Utilisateur WHERE role = 'user';";
    $usersList = array();
    foreach ($link->query($query) as $row) {
        $usersList[] = $row['pseudo'];
    }
    return $usersList;
}

/* Récupère le nombre total d'utilisateurs (admins inclus) */
function getNumUsers($link)
{
    $query = "SELECT COUNT(pseudo) AS numUsers FROM Utilisateur";
    $result = executeQuery($link, $query);
    $numUsers = $result->fetch_assoc();

    return $numUsers['numUsers'];
}

/* Récupère le nombre de photos postées par chaque utilisateur */
function getNumUsersPhotos($link)
{
    $query = "SELECT U.pseudo, COUNT(P.photoId) AS numPhotos FROM Utilisateur U JOIN Photo P ON U.pseudo = P.pseudo GROUP BY U.pseudo";
    $result = executeQuery($link, $query);

    $tabUsersPhotos = array();

    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($rows as $row) {
        $tabUsersPhotos[$row["pseudo"]] = $row["numPhotos"];
    }

    return $tabUsersPhotos;
}

/* Récupère le nombre de photos postées dans chaque catégorie */
function getNumCatPhotos($link)
{
    $query = "SELECT C.nomCat, COUNT(P.photoId) AS numPhotos FROM Categorie C JOIN Photo P ON C.catId = P.catId GROUP BY C.nomCat";

    $result = executeQuery($link, $query);

    $tabCatPhotos = array();

    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($rows as $row) {
        $tabCatPhotos[$row["nomCat"]] = $row["numPhotos"];
    }

    return $tabCatPhotos;
}
