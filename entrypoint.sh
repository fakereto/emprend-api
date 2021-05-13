#!/bin/bash
set -eo pipefail
shopt -s nullglob

# Docker general functions for use on docker entrypoint files

# logging functions
docker_log() {
	local type="$1"; shift
	printf '%s [%s] [Entrypoint]: %s\n' "$(date --rfc-3339=seconds)" "$type" "$*"
}

# logging a note
log_note() {
	docker_log Note "$@"
}
# logging a warning
log_warn() {
	docker_log Warn "$@" >&2
}
# logging a error
log_error() {
	docker_log ERROR "$@" >&2
	exit 1
}

# check to see if this file is being run or sourced from another script
_is_sourced() {
        # https://unix.stackexchange.com/a/215279
        [ "${#FUNCNAME[@]}" -ge 2 ] \
                && [ "${FUNCNAME[0]}" = '_is_sourced' ] \
                && [ "${FUNCNAME[1]}" = 'source' ]
}

_main() {
    #Run base phpfpm image entrypoint
    /var/www/docker-phpfpm-entrypoint.sh
    
    sed -i "s~ENV_SERVER_NAME~${ENV_SERVER_NAME}~g" ${NGINX_CONF_DIR}/sites-enabled/app.conf
    sleep 10s
    #php artisan migrate
    php artisan migrate
    php artisan db:seed
    exec "$@"
}

# If we are sourced from elsewhere, don't perform any further actions
if ! _is_sourced; then
    _main "$@"
fi