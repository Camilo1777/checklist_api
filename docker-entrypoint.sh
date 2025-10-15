#!/bin/bash
set -e

# Si la plataforma (Railway) exporta PORT, actualizar Apache para usarlo
if [ -n "${PORT}" ]; then
  echo "INFO: Ajustando Apache para escuchar en el puerto ${PORT}"
  # Actualizar ports.conf
  if [ -f /etc/apache2/ports.conf ]; then
    sed -ri "s/Listen\s+[0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf || true
  fi
  # Actualizar virtual hosts
  for f in /etc/apache2/sites-enabled/*.conf; do
    [ -f "$f" ] || continue
    sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" "$f" || true
  done
fi

# Ejecutar comando pasado (normalmente apache2-foreground)
exec "$@"
