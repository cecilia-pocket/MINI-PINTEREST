<?php

/* Vérifie si le pseudo donné en paramètre est disponible dans la BDD, si disponible => renvoie vrai */
function checkAvailability($pseudo, $link)
{
    $query = "SELECT U.pseudo FROM Utilisateur U WHERE U.pseudo = '". $pseudo ."';";
    $result = executeQuery($link, $query);
    return mysqli_num_rows($result) == 0;
}

/* Inscrit l'utilisateur dans la BDD dans la table 'Utilisateur' avec son pseudo, son mdp, le statut 'disconnected' et le rôle 'user' */
function register($pseudo, $hashPwd, $link)
{
    $query = "INSERT INTO Utilisateur VALUES ('". $pseudo ."', '". $hashPwd ."', 'disconnected', 'user');";
    executeUpdate($link, $query);
}

/* Change l'état de l'utilisateur en 'connected' quand il se connecte sur le site */
function setConnected($pseudo, $tempsConnexion, $link)
{
    $query = "UPDATE Utilisateur SET etat = 'connected' WHERE pseudo = '" . $pseudo . "';";
    executeUpdate($link, $query);
}

/* Change l'état de l'utilisateur en 'disconnected' quand il se déconnecte sur le site */
function setDisconnected($pseudo, $link)
{
    $query = "UPDATE Utilisateur SET etat = 'disconnected' WHERE pseudo = '" . $pseudo . "';";
    executeUpdate($link, $query);
}

/* Vérifie si l'utilisateur existe à la connexion, s'il existe => renvoie vrai */
function getUser($pseudo, $hashPwd, $link)
{
    $query = "SELECT * FROM Utilisateur U WHERE U.pseudo = '" . $pseudo . "' AND U.mdp = '" . $hashPwd . "';";
    $result = executeQuery($link, $query);
    return mysqli_num_rows($result) >= 1;
}

/* Récupère le rôle de l'utilisateur connecté */
function getRole($pseudo, $link)
{
    $query = "SELECT role FROM Utilisateur WHERE pseudo = '" . $pseudo . "'";
    $tabRole = executeQuery($link, $query);
    $role = $tabRole->fetch_assoc();

    return $role['role'];
}

/* Change le mdp de l'utilisateur */
function updateMdp($pseudo, $hashPwd, $link)
{
    $query = "UPDATE Utilisateur SET mdp = '" . $hashPwd . "' WHERE pseudo = '" . $pseudo . "'";
    executeUpdate($link, $query);
}

/* Vérifie si l'utilisateur est un admin ou un contributeur (= la personne qui a posté la photo) */
function isAdminOrContributor($utilisateur, $imageNom, $link)
{
    $query_admin = "SELECT * FROM Utilisateur WHERE pseudo = '" . $utilisateur . "' AND role = 'admin'";
    $result_admin = executeQuery($link, $query_admin);
    if (mysqli_num_rows($result_admin) == 1) {
        $isAdmin = true;
    } else {
        $isAdmin = false;
    }

    $query_contributor = "SELECT * FROM Utilisateur U JOIN Photo P ON U.pseudo = P.pseudo WHERE U.pseudo = '" . $utilisateur . "' AND P.nomFich = '" . $imageNom . "'";
    $result_contributor = executeQuery($link, $query_contributor);
    if (mysqli_num_rows($result_contributor) == 1) {
        $isContributor = true;
    } else {
        $isContributor = false;
    }

    return ($isAdmin || $isContributor);
}

/* Récupère les noms des photos que l'utilisateur mis en paramètre a posté */
function getPhotosUser($utilisateur, $link)
{
    $query = "SELECT nomFich FROM Photo WHERE pseudo = '" . $utilisateur . "'";
    $pathsPhotosUser = array();
    foreach ($link->query($query) as $row) {
        $pathsPhotosUser[] = $row['nomFich'];
    }
    return $pathsPhotosUser;
}
