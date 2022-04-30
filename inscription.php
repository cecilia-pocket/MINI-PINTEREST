<!--     PAGE D'INSCRIPTION     -->

<?php
session_start();
require_once 'php/bd.php';
require_once 'php/nav_connexion.php';
require_once 'php/administrateur.php';
require_once 'php/utilisateur.php';
require_once 'php/photo.php';

$link = getConnection($dbHost, $dbUser, $dbPwd, $dbName);
$connectState = getConnectState();

$stateMsg = "";

if (isset($_POST["valider"])) {
    $pseudo = $_POST["pseudo"];
    $hashMdp = md5($_POST["mdp"]);
    $hashConfirmMdp = md5($_POST["confirmMdp"]);

    $available = checkAvailability($pseudo, $link);

    if ($hashMdp == $hashConfirmMdp) {
        if ($available) {
            register($pseudo, $hashMdp, $link);
            header('Location: connexion.php');
        } else {
            $stateMsg = "Le pseudo demandé est déjà utilisé";
        }
    } else {
        $stateMsg = "Les mots de passe ne correspondent pas, veuillez réessayer";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Application mini-Pinterest</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <div class="navigation">
    <div class="nav-utilisateur">
    </div>
    <div class="nav-boutons">
      <a class="boutonNav" href="./index.php">accueil</a>
      <?php connectButton($connectState); ?>
    </div>
  </div>

  <div class="connection">
    <div class="connec-titre">Inscription</div>
    <div class="errorMsg"><?php echo $stateMsg; ?></div>
    <?php if (isset($successMsg)) {
    echo $successMsg;
} ?>
    <form action="inscription.php" method="POST">
      <div class="formRegister">
        <div class="loginInfo"><label for="pseudo">Pseudo : </label><input id="pseudo "type="text" name="pseudo"></div>
        <div class="loginInfo"><label for"mdp">Mot de passe : </label><input type="password" name="mdp"></div>
        <div class="loginInfo"><label for"mdp">Mot de passe : </label><input type="password" name="confirmMdp"></div>
        <div style="text-align: center"><input class="button" type="submit" name="valider" value="S'inscrire"></div>
      </div>
    </form>
    <br />
    <a href="connexion.php">Déjà inscrit ?</a>
  </div>

</body>

</html>
