<!--     PROFIL D'UN ADMIN     -->

<?php
session_start();
require_once 'php/bd.php';
require_once 'php/nav_connexion.php';
require_once 'php/administrateur.php';
require_once 'php/utilisateur.php';
require_once 'php/photo.php';

$link = getConnection($dbHost, $dbUser, $dbPwd, $dbName);
$connectState = getConnectState();

$adminsList = getAllAdmins($link);
$usersList = getAllUsers($link);

$numUsers = getNumUsers($link);
$numUsersPhotos = getNumUsersPhotos($link);
$numCatPhotos = getNumCatPhotos($link);

function displayPseudoList($array)
{
    $html = '';
    foreach ($array as $value) {
        $html .= $value . ' ';
    }
    echo $html;
}

function displayTabStats($array)
{
    $html = '';
    foreach ($array as $key => $value) {
        $html .= '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';
    }
    echo $html;
}

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
  </div>

  <div class="listeAdmins"><div class="grand-titre"><span>Liste des administrateurs :</span></div><br />
  <?php displayPseudoList($adminsList);?></div>

  <div class="listeUsers"><div class="grand-titre"><span>Liste des utilisateurs :</span></div><br />
  <?php displayPseudoList($usersList);?></div>

  <div class="statistiques">
    <div class="grand-titre"><span>Les statistiques</span></div><br />
    • <b>Nombre total d'utilisateurs :</b> <?php echo $numUsers; ?><br /><br />
    • <b>Nombre de photos téléchargées par chaque utilisateur :</b><br />
    <table>
      <thead>
        <th>Utilisateur</th><th>Nombre de photos postées</th>
      </thead>
      <tbody>
      <?php displayTabStats($numUsersPhotos); ?>
      </tbody>
    </table>
    • <b>Nombre de photos téléchargées dans chaque catégorie :</b><br />
    <table>
      <thead>
        <th>Catégorie</th><th>Nombre de photos postées</th>
      </thead>
      <tbody>
      <?php displayTabStats($numCatPhotos); ?>
      </tbody>
    </table>
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
