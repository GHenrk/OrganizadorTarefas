<?php 

function ValidaInfo($info){
 return isset($info) && !empty($info);
}

function ValidaCampo($campo, $endereco, $complementoMsg){
  if (!ValidaInfo($_POST[$campo])){
    header("Location: $endereco?error=". urlencode("$complementoMsg"));
    exit;
  }
  return $_POST[$campo];
}




?>