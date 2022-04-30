<!--     PAGE D'UN UTILISATEUR     -->

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

if (isset($_POST["changerMdp"])) {
    $hashMdp = md5($_POST["nouvMdp"]);
    $hashConfirmMdp = md5($_POST["confirmNouvMdp"]);

    if ($hashMdp == $hashConfirmMdp) {
        updateMdp($utilisateur, $hashMdp, $link);
        $confirmMsg = "Le mot de passe a bien été changé";
        header("refresh:5;url=profilUtilisateur.php");
    } else {
        $errorMsg = "Les mots de passe ne correspondent pas, veuillez réessayer";
    }
}

$pathsPhotosUser = getPhotosUser($utilisateur, $link);

function displayPhotos($array)
{
    foreach ($array as $value) {
        $html = '<a href="detail.php?img_nomFich='. $value . '"><img class="" src="' . $value . '"></a>';
        echo $html;
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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

  <div class="blocProfil">
    <div class="confirmMsg"><?php echo $confirmMsg; ?></div>
    <div class="errorMsg"><?php echo $errorMsg; ?></div>
    <div class="grand-titre"><span>Vos informations personnelles :</span> <button onclick="toggle()">Modifier <i class="fas fa-cog"></i></button></div>
    <div id="profilInfo">
      <b>Pseudo :</b> <?php echo $utilisateur; ?><br /><br />
      <b>Mot de passe :</b> ••••••••••<br /><br />
      <b>Rôle :</b> <?php echo $role; ?>
    </div>
    <div id="profilInfoModif">
      <form action="profilUtilisateur.php" method="POST">
          <div class="mdpProfil"><label for"nouvMdp"><b>Nouveau mot de passe :</b> </label><input type="password" name="nouvMdp"></div>
          <div class="mdpProfil"><label for"confirmNouvMdp"><b>Confirmer nouveau mot de passe :</b> </label><input type="password" name="confirmNouvMdp"></div>
          <div><input class="button" type="submit" name="changerMdp" value="Changer le mot de passe"></div>
      </form>
    </div>
    <div class="grand-titre"><span>Vos images :</span></div>
  </div>
  <div class="galerieProfil">
    <div class="gutter-size"></div>
    <?php displayPhotos($pathsPhotosUser); ?>
  </div>

  <script>
  $(".galerieProfil").imagesLoaded(function() {
    $(".galerieProfil").masonry({
      columnWidth: "img",
      itemSelector: "img",
      gutter: ".gutter-size",
      fitWidth: true
    });
  });

  function toggle() {
    const x = document.getElementById("profilInfo");
    const y = document.getElementById("profilInfoModif");
    if (x.style.display === "none") {
      x.style.display = "block";
      y.style.display = "none";
    } else {
      x.style.display = "none";
      y.style.display = "block";
    }
  }
  </script>
</body>
</html>
