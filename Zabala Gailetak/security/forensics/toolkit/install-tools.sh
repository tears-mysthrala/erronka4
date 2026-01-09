#!/bin/bash
set -e

echo "[+] Installing Zabala Gailetak Forensics Toolkit"

# Disk forensics
apt-get install -y sleuthkit autopsy foremost testdisk photorec

# Memory forensics
pip3 install volatility3

# Network forensics
apt-get install -y wireshark tcpdump tshark

# Log analysis
apt-get install -y jq

# Create evidence directory
mkdir -p /evidence/{disk,memory,network,mobile,logs}
chmod 700 /evidence

echo "[✓] Forensics toolkit installation complete"
