# EMQX — certificados TLS de desarrollo

Generar certificados autofirmados para el listener `8883` (mqtts):

```bash
mkdir -p infra/emqx/certs && cd infra/emqx/certs
openssl req -x509 -newkey rsa:2048 -nodes -keyout key.pem -out cert.pem \
  -days 365 -subj "/CN=localhost"
```

EMQX monta `infra/emqx/certs` en `/opt/emqx/etc/certs`. Para producción se
reemplazan por certificados reales (Let's Encrypt). Los `.pem` están ignorados
por git (ver `.gitignore`).
