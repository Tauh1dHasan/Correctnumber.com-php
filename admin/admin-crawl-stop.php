<?php include("inc-main.php"); ?>
<?php
// *** Logout the current user.
$logoutGoTo = "admin.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['CrownlingPending'] = NULL;
unset($_SESSION['CrownlingPending']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
