<?php
// Clave secreta para generar y verificar los tokens JWT
$secret_key = "checklist_super_secret_key_2025";
$issuer_claim = "localhost"; // quien emite el token
$audience_claim = "appchecklist"; // quien lo recibe
$issuedat_claim = time(); // fecha de creación del token
$notbefore_claim = $issuedat_claim; // válido desde ahora
$expire_claim = $issuedat_claim + 3600; // expira en 1 hora
?>
