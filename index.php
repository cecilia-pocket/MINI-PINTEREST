<!--     PAGE D'ACCUEIL     -->

<?php
session_start();
require_once 'php/bd.php';
require_once 'php/nav_connexion.php';
require_once 'php/administrateur.php';
require_once 'php/utilisateur.php';
require_once 'php/photo.php';

$link = getConnection($dbHost, $dbUser, $dbPwd, $dbName);
$connectState = getConnectState();

if (empty($_SESSION)) {
    $role = "";
} else {
    $role = getRole($utilisateur, $link);
}

if (empty($_GET)) {
    $pathsCatList = getImagesPaths($link);
} else {
    $nomCat = $_GET["img_nomCat"];
    $pathsCatList = getImgCategorie($link, $nomCat);
}

if (isset($_POST['deconnexion'])) {
    setDisconnected($utilisateur, $link);
    session_destroy();
    header('Location: index.php');
}

function displayPhotos($array)
{
    foreach ($array as $value) {
        $html = '<a href="detail.php?img_nomFich='. $value . '"><img class="" src="' . $value . '"></a>';
        echo $html;
    }
}

$nomCat = "Tout";

if (isset($_POST['submit'])) {
    $selectedVal = $_POST['categorie'];

    switch ($selectedVal) {
      case 'Tout':
        $nomCat = $selectedVal;
        $pathsCatList = getImagesPaths($link);
        break;
      case 'Chiens':
        $nomCat = $selectedVal;
        $pathsCatList = getImgCategorie($link, 1);
        break;
      case 'Chats':
        $nomCat = $selectedVal;
        $pathsCatList = getImgCategorie($link, 2);
        break;
      case 'Chèvres':
        $nomCat = $selectedVal;
        $pathsCatList = getImgCategorie($link, 3);
        break;
      case 'Singes':
        $nomCat = $selectedVal;
        $pathsCatList = getImgCategorie($link, 4);
        break;
      case 'Quokkas':
        $nomCat = $selectedVal;
        $pathsCatList = getImgCategorie($link, 5);
        break;
      case 'Lapins':
        $nomCat = $selectedVal;
        $pathsCatList = getImgCategorie($link, 6);
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Application mini-Pinterest</title>
  <link rel="stylesheet" href="css/style.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
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

  <div class="blocprincipal">
    <div class="categories">Choisir une catégorie :
      <form action="index.php" method="POST">
        <select name="categorie">
        <option value="Tout">Tout</option>
        <option value="Chiens">Chiens</option>
        <option value="Chats">Chats</option>
        <option value="Chèvres">Chèvres</option>
        <option value="Singes">Singes</option>
        <option value="Quokkas">Quokkas</option>
        <option value="Lapins">Lapins</option>
        </select>
      <input class="button" type="submit" name="submit" value="Valider" />
      </form>
      <div class="grand-titre"><span>Catégorie :</span> <cat style="font-size: 1.3em;"><?php echo $nomCat; ?></cat></div>
    </div>
    <div class="galerie">
      <div class="gutter-size"></div>
      <?php displayPhotos($pathsCatList); ?>
    </div>
  </div>

  <script>
  $(".galerie").imagesLoaded(function() {
    $(".galerie").masonry({
      columnWidth: "img",
      itemSelector: "img",
      gutter: ".gutter-size",
      fitWidth: true
    });
  });
  </script>
</body>

</html>
