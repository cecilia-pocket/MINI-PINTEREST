<!--     AJOUT D'UNE PHOTO     -->

<?php
session_start();
require_once 'php/bd.php';
require_once 'php/nav_connexion.php';
require_once 'php/administrateur.php';
require_once 'php/utilisateur.php';
require_once 'php/photo.php';

$link = getConnection($dbHost, $dbUser, $dbPwd, $dbName);
$connectState = getConnectState();

$role = getRole($utilisateur, $link);

$errorMsg = "";
$confirmMsg = "";

if (isset($_POST['ajouter'])) {
    $getImage = basename($_FILES["fileToUpload"]["name"]);
    $getDescription = $_POST["description"];
    $getCategorie = $_POST["categorie"];
    $getUser = $_SESSION["user"];
    $imageFileType = strtolower(pathinfo($getImage, PATHINFO_EXTENSION));
    $uploadOk = addPhoto($getImage, $getDescription, $getCategorie, $getUser, $link);
    if ($uploadOk == 1) {
        $new_path = renamePhoto($imageFileType, $link);
        header("refresh:2;url=detail.php?img_nomFich=" . $new_path);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Application mini-Pinterest</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
</head>

<body>

  <div class="navigation">
    <div class="nav-utilisateur">
      <?php showUser($connectState, $utilisateur, $link); ?>
    </div>
    <div class="nav-boutons">
      <a class="boutonNav" href="./index.php">accueil</a>
      <?php connectButton($connectState); ?>
    </div>
  </div>

  <div class="ajouter">
    <div class="errorMsg"><?php echo $errorMsg; ?></div>
    <div class="confirmMsg"><?php echo $confirmMsg; ?></div>
    <div class="grand-titre"><span>Ajouter une image</span></div>
    <form action="ajouter.php" method="post" enctype="multipart/form-data">
      <b>Image :</b><br>
      <input type="file" name="fileToUpload" id="fileToUpload" required><br><br>

      <b>Description :</b><br />
      <input type="text" name="description" id="description" required> <br /> <br />

      <b>Catégorie :</b><br />
  	  <select name="categorie">
          <option value="1">Chiens</option>
          <option value="2">Chats</option>
          <option value="3">Chèvres</option>
          <option value="4">Singes</option>
          <option value="5">Quokkas</option>
          <option value="6">Lapins</option>
      </select><br /><br />
      <input class="button" type="submit" value="Envoyer" name="ajouter">
  </form>
  </div>

</body>
</html>
