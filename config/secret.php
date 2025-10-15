<?php
// Configuración de JWT basada en variables de entorno (con valores por defecto para desarrollo)
$secret_key = getenv('SECRET_KEY') ?: 'checklist_super_secret_key_2025';
$issuer_claim = getenv('JWT_ISSUER') ?: 'localhost';
$audience_claim = getenv('JWT_AUDIENCE') ?: 'appchecklist';
$issuedat_claim = time();
$notbefore_claim = $issuedat_claim;
$expire_seconds = (int) (getenv('JWT_EXPIRE_SECONDS') ?: 3600);
$expire_claim = $issuedat_claim + $expire_seconds;
?>
