<?php

/* Récupère les chemins de toutes les images */
function getImagesPaths($link)
{
    $query = "SELECT P.nomFich FROM Photo P";
    $pathsList = array();
    foreach ($link->query($query) as $row) {
        $pathsList[] = $row['nomFich'];
    }
    return $pathsList;
}

/* Récupère les chemins des images de la catégorie mis en paramètre */
function getImgCategorie($link, $catId)
{
    $query = "SELECT P.nomFich FROM Photo P WHERE catId = " . $catId . ";";
    $pathsCatList = array();
    foreach ($link->query($query) as $row) {
        $pathsCatList[] = $row['nomFich'];
    }
    return $pathsCatList;
}

/* Récupère le nom de la catégorie d'après son nom (unique) */
function getNomCat($imageNom, $catId, $link)
{
    $query = "SELECT C.nomCat FROM Categorie C JOIN Photo P ON C.catId = " . $catId . " WHERE P.nomFich = '" . $imageNom . "';";
    $nomCat = executeQuery($link, $query);
    $assocNomCat = $nomCat->fetch_assoc();

    return $assocNomCat;
}

/* Récupère les informations d'une image en fonction de son nom (unique) */
function detail($imageNom, $link)
{
    $query = "SELECT nomFich, description, catId, pseudo FROM Photo WHERE nomFich = '" . $imageNom . "';";
    $tabDetail = executeQuery($link, $query);
    $assocTabDetail = $tabDetail->fetch_assoc();

    return $assocTabDetail;
}

/* Ajoute une photo dans le serveur et dans la BDD */
function addPhoto($imageNom, $imageDesc, $catId, $pseudo, $link)
{
    global $errorMsg, $confirmMsg;
    /* AJOUTE LA PHOTO SUR LE SERVEUR */
    $target_dir = "./image/";
    $target_file = $target_dir . $imageNom;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $new_target_file = $target_dir . "temp";
    $uploadOk = 1;

    // Vérifie si le fichier est bien une image ou non
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $errorMsg .= "Le fichier n'est pas une image.<br />";
            $uploadOk = 0;
        }
    }

    // Vérifie la taille du fichier
    if ($_FILES["fileToUpload"]["size"] > 100000) {
        $errorMsg .= "Erreur, le fichier est trop lourd : il ne doit pas dépasser 100 Ko.<br />";
        $uploadOk = 0;
    }

    // Autorise seulement certains formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif") {
        $errorMsg .= "Erreur, seuls les formats JPG, JPEG, PNG et GIF sont autorisés.<br />";
        $uploadOk = 0;
    }

    // Vérifie si $uploadOk est à 0 à cause d'une erreur
    if ($uploadOk == 0) {
        $errorMsg .= "Erreur, le fichier n'a pas été téléchargé.<br />";
    // Si tout est ok ($upload == 1) alors on essaie de télécharger le fichier
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $new_target_file)) {
            $confirmMsg = "Le fichier ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " a bien été téléchargé.<br />";

            /* AJOUTE LA PHOTO DANS LA BASE DE DONNEES */
            $query = "INSERT INTO Photo(nomFich, description, catId, pseudo) VALUES ('" . $new_target_file . "', '" . $imageDesc . "', " . $catId . ", '" . $pseudo . "')";
            executeUpdate($link, $query);
        } else {
            $errorMsg .= "Erreur lors du téléchargement du fichier.<br />";
        }
    }

    return $uploadOk;
}

/* Récupère l'id de la photo (avec un nom temporaire) qui vient d'être ajoutée */
function getTempPhotoId($link)
{
    $query = "SELECT photoId FROM Photo WHERE nomFich = './image/temp'";
    $result = executeQuery($link, $query);
    $tabResult = mysqli_fetch_assoc($result);
    return $tabResult['photoId'];
}

/* Renomme la photo qui vient d'être ajoutée */
function renamePhoto($fileType, $link)
{
    $photoId = getTempPhotoId($link);

    /* RENOMME SUR LE SERVEUR */
    $new_path = "./image/DSC_" . $photoId . "." . $fileType;
    rename("./image/temp", $new_path);

    /* RENOMME DANS LA BASE DE DONNEES */
    $query = "UPDATE Photo SET nomFich = './image/DSC_" . $photoId . "." . $fileType . "' WHERE photoId = " . $photoId;
    executeUpdate($link, $query);

    return $new_path;
}

/* Supprime la photo du serveur et de la BDD */
function deletePhoto($imageNom, $link)
{
    /* SUPPRIME DU SERVEUR */
    unlink($imageNom);

    /* SUPPRIME DE LA BASE DE DONNEES */
    $query = "DELETE FROM Photo WHERE nomFich = '" . $imageNom . "'";
    executeUpdate($link, $query);

    $imageNom = str_replace(array( '..', '/', '\\', ':' ), '', $imageNom);
}

/* Modifie les détails de la photo */
function editPhoto($imageNom, $imageDesc, $catId, $link)
{
    $query = "UPDATE Photo SET description = '" . $imageDesc . "', catId = " . $catId . " WHERE nomFich = '" . $imageNom . "'";
    executeUpdate($link, $query);
}
