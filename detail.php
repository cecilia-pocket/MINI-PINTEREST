<!--     DETAIL D'UNE PHOTO     -->

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

$imageNom = $_GET["img_nomFich"];

$tabDetail = detail($imageNom, $link);

$tabNomCat = getNomCat($imageNom, $tabDetail['catId'], $link);

function displayPhoto($nomFich)
{
    $html = '<img class="" src="' . $nomFich . '">';
    echo $html;
}

$confirmMsg = "";
$errorMsg = "";

if (isset($_POST['modifier'])) {
    $nouvImageDesc = $_POST["nouvDescription"];
    $nouvCatId = $_POST["nouvCategorie"];

    if (($nouvImageDesc != $tabDetail['description']) || ($nouvCatId != $tabDetail['catId'])) {
        editPhoto($imageNom, $nouvImageDesc, $nouvCatId, $link);
        $confirmMsg = "La photo a bien été modifiée";
        header('refresh:2;url=detail.php?img_nomFich=' . $imageNom);
    } else {
        $errorMsg = "La description et la catégorie n'ont pas été modifiées";
    }
}

if (isset($_POST['supprimer'])) {
    deletePhoto($imageNom, $link);
    header("refresh:3;url=index.php");
    $confirmMsg = "La photo a bien été supprimée";
}

function showAdminContri()
{
    global $utilisateur, $imageNom, $link;
    $boolAdminContri = isAdminOrContributor($utilisateur, $imageNom, $link);

    if ($boolAdminContri) {
        echo '<div class="optionsBoutons">
          <button class="button" onclick="toggle()">Modifier</button>
          <form action="" method="POST">
            <input class="button" type="submit" name="supprimer" value="Supprimer">
          </form>
        </div>';
    } else {
        echo '';
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

  <div class="confirmMsg"><?php echo $confirmMsg; ?></div>
  <div class="errorMsg" style="margin: 30px auto 0 auto;"><?php echo $errorMsg; ?></div>

  <div class="detailphoto">
    <div class="photo">
      <?php
        displayPhoto($tabDetail['nomFich']);
      ?>
    </div>
    <div class="nomImage">
      <b>Nom du fichier</b> :
      <?php
        echo $tabDetail['nomFich'];
      ?>
    </div>
    <div class="description">
      <b>Description</b> :
      <?php
        echo $tabDetail['description'];
      ?>
    </div>
    <div class="categorie">
      <b>Catégorie</b> :
      <a href="index.php?img_nomCat=<?php
        echo $tabDetail['catId'];
      ?>">
      <?php
        echo $tabNomCat['nomCat'];
      ?>
    </a>
    </div>
    <div class="contributeur">
      <b>Ajouté par</b> :
        <?php
          echo $tabDetail['pseudo'];
        ?>
    </div>
  </div>

  <?php showAdminContri(); ?>

  <div id="formModif">
    <form action="" method="POST">
      <div class="modifInfo">
        <label for="nouvDescription">Description : </label>
        <input id="nouvDescription" type="text" name="nouvDescription"></div>
      <div class="modifInfo">
        <label for="nouvCategorie">Catégorie : </label>
  	    <select name="nouvCategorie">
          <option value="1">Chiens</option>
          <option value="2">Chats</option>
          <option value="3">Chèvres</option>
          <option value="4">Singes</option>
          <option value="5">Quokkas</option>
          <option value="6">Lapins</option>
        </select>
      </div>
      <div style="text-align: center;"><input class="button" type="submit" value="Valider" name="modifier"></div>
    </form>
  </div>

  <script>
  function toggle() {
    const x = document.getElementById("formModif");
    if (x.style.display === "flex") {
      x.style.display = "none";
    } else {
      x.style.display = "flex";
    }
  }
  </script>

</body>

</html>
