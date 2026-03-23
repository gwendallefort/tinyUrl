#!/usr/bin/env bash
set -euo pipefail

# Restore the latest backup created by .github/workflows/deploy.yml.
#
# Required environment variables:
# - HOSTINGER_USER
# - HOSTINGER_HOST
# - HOSTINGER_PORT
# - HOSTINGER_PATH
# - SSH_PRIVATE_KEY_PATH (path to private key file)
#
# Optional:
# - SSH_OPTS (extra ssh options)
# - AUTO_CONFIRM=true (skip prompt)
#
# Usage:
#   HOSTINGER_USER=... HOSTINGER_HOST=... HOSTINGER_PORT=22 \
#   HOSTINGER_PATH=~/domains/example/public_html SSH_PRIVATE_KEY_PATH=~/.ssh/id_rsa \
#   ./scripts/restore-last-backup.sh

required_vars=(HOSTINGER_USER HOSTINGER_HOST HOSTINGER_PORT HOSTINGER_PATH SSH_PRIVATE_KEY_PATH)
for var in "${required_vars[@]}"; do
  if [[ -z "${!var:-}" ]]; then
    echo "Missing required variable: $var" >&2
    exit 1
  fi
done

if [[ ! -f "$SSH_PRIVATE_KEY_PATH" ]]; then
  echo "SSH key not found: $SSH_PRIVATE_KEY_PATH" >&2
  exit 1
fi

SSH_OPTS_DEFAULT="-o IdentitiesOnly=yes -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -o ConnectTimeout=60 -o ServerAliveInterval=30 -o ServerAliveCountMax=6 -o TCPKeepAlive=yes"
SSH_OPTS="${SSH_OPTS:-$SSH_OPTS_DEFAULT}"

ssh_cmd=(ssh -i "$SSH_PRIVATE_KEY_PATH")
for opt in $SSH_OPTS; do
  ssh_cmd+=("$opt")
done
ssh_cmd+=(-p "$HOSTINGER_PORT" "${HOSTINGER_USER}@${HOSTINGER_HOST}")

echo "Resolving remote path..."
REMOTE_HOME="$("${ssh_cmd[@]}" "printf '%s' \"\$HOME\"")"
if [[ "${HOSTINGER_PATH}" =~ ^~(/|$) ]]; then
  HOSTINGER_PATH="${HOSTINGER_PATH/#\~/$REMOTE_HOME}"
fi

BACKUP_BASE="${HOSTINGER_PATH}/backups"
CURRENT_PATH="${HOSTINGER_PATH}/current"

echo "Looking for latest backup in ${BACKUP_BASE}..."
LATEST_BACKUP="$("${ssh_cmd[@]}" "ls -dt \"${BACKUP_BASE}\"/backup-* 2>/dev/null | head -n 1 || true")"

if [[ -z "$LATEST_BACKUP" ]]; then
  echo "No backup found in ${BACKUP_BASE}" >&2
  exit 1
fi

echo "Latest backup found: ${LATEST_BACKUP}"
echo "Target directory to restore: ${CURRENT_PATH}"

if [[ "${AUTO_CONFIRM:-false}" != "true" ]]; then
  read -r -p "This will overwrite ${CURRENT_PATH}. Continue? [y/N] " reply
  if [[ ! "$reply" =~ ^[Yy]$ ]]; then
    echo "Restore cancelled."
    exit 0
  fi
fi

echo "Restoring backup to current..."
"${ssh_cmd[@]}" "mkdir -p \"${CURRENT_PATH}\" && rsync -a --delete \"${LATEST_BACKUP}/\" \"${CURRENT_PATH}/\""

echo "Restore complete."
