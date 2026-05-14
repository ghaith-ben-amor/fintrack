web: symfony local:server:start --port=${PORT:-8000} --force-ip=0.0.0.0
worker: php bin/console messenger:consume async -vv
