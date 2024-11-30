<?php

function RedirecionaSeNaoAutenticado($page)
{

  if (
    !isset($_SESSION['usuario_id']) || $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT'] ||
    $_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR']
  ) {
    session_unset();
    session_destroy();
    if (!isset($page) || $page == '')
      header("Location: login.php");
    else
      header(("Location: $page"));
    exit;
  }
}


function AlgumUsuarioAutenticado()
{
  return isset($_SESSION["usuario_id"])
    && $_SESSION["user_agent"] == $_SERVER['HTTP_USER_AGENT'] &&
    $_SESSION['user_ip'] == $_SERVER['REMOTE_ADDR'];
}
?>