# Server Hardening SOP (Debian 12 Copy-Paste)

**Target:** All Servers (App, Data, SecOps, OT)
**User:** Root

## 1. Basic System Secure

Run these commands on every new server immediately after installation.

```bash
# 1. Update & Install Tools
apt update && apt upgrade -y
apt install -y ufw fail2ban curl git htop

# 2. Setup Non-Root User (Interactive)
adduser admin
usermod -aG sudo admin

# 3. Secure Shared Memory
echo "tmpfs /run/shm tmpfs defaults,noexec,nosuid 0 0" >> /etc/fstab
mount -o remount /run/shm
```

## 2. SSH Hardening (Critical)

Prevents root login and password brute-forcing.

```bash
# Create Hardening Config File (Debian 12 method)
cat <<EOF > /etc/ssh/sshd_config.d/99-hardening.conf
PermitRootLogin no
PasswordAuthentication no
X11Forwarding no
EOF

# Restart SSH
systemctl restart sshd
```
*Warning: Ensure you have SSH keys setup for `admin` user before disabling passwords!*
*If you haven't set up keys yet, temporarily use `PasswordAuthentication yes` and change it later.*

## 3. Host Firewall (UFW) Configuration

### For ZG-App (Web Server)
```bash
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 80/tcp
ufw allow 443/tcp
ufw enable
```

### For ZG-Data (Database)
```bash
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
# Allow Postgres ONLY from App Server
ufw allow from 192.168.20.10 to any port 5432
# Allow Redis ONLY from App Server
ufw allow from 192.168.20.10 to any port 6379
ufw enable
```

### For ZG-SecOps (Wazuh)
```bash
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 443/tcp    # Wazuh Dashboard
ufw allow 1514/tcp   # Agent communication
ufw allow 1515/tcp   # Enrollment
ufw enable
```

### For ZG-OT (Industrial)
```bash
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 8080/tcp   # OpenPLC Web
ufw allow 502/tcp    # Modbus
ufw enable
```

## 4. Fail2Ban Setup
Protect SSH from brute force.

```bash
# Create local config
cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local

# Enable SSH jail
cat <<EOF >> /etc/fail2ban/jail.local
[sshd]
enabled = true
port = ssh
filter = sshd
logpath = /var/log/auth.log
maxretry = 3
bantime = 3600
EOF

systemctl restart fail2ban
systemctl enable fail2ban
```