<!--     PAGE DE CONNEXION     -->

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

    $link = getConnection($dbHost, $dbUser, $dbPwd, $dbName);

    $exist = getUser($pseudo, $hashMdp, $link);
    if ($exist) {
        $tempsConnexion = time();
        setConnected($pseudo, $tempsConnexion, $link);
        $_SESSION["user"] = $pseudo;
        $_SESSION["logged"] = time();
        header('Location: index.php');
    } else {
        $stateMsg = "Le couple pseudo/mot de passe ne correspond à aucun utilisateur enregistré";
    }
}

if (isset($_GET["subscribe"])) {
    $successMsg = "<div class='sucessMsg'>L'inscription a bien été effectué, vous pouvez vous connecter</div>";
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
    <div class="connec-titre">Connexion</div>
    <div class="errorMsg"><?php echo $stateMsg; ?></div>
    <?php if (isset($successMsg)) {
    echo $successMsg;
} ?>
    <form action="connexion.php" method="POST">
          <div class="formConnection">
              <div class="loginInfo"><label for="pseudo">Pseudo : </label><input id="pseudo" type="text" name="pseudo"></div>
              <div class="loginInfo"><label for"mdp">Mot de passe : </label><input type="password" name="mdp"></div>
              <div style="text-align: center"><input class="button" type="submit" name="valider" value="Se connecter"></div>
          </div>
    </form>
    <br />
    <a href="inscription.php">Première connexion ?</a>
  </div>

</body>

</html>
