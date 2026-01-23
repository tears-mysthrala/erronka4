# Network Segmentation SOP (Debian/NFTables)

**Version:** 2.0 (Configuration Reference)
**Role:** ZG-Gateway (Router/Firewall)

## 1. Interface Configuration (`/etc/network/interfaces`)

This configuration defines the Gateway as the router for all internal subnets.

```bash
# /etc/network/interfaces

source /etc/network/interfaces.d/*

auto lo
iface lo inet loopback

# WAN (Eth0) - Connected to Internet (Isard Bridge)
allow-hotplug eth0
iface eth0 inet dhcp

# LAN (Eth1) - Connected to Internal Network
# We use a single interface with aliases for each VLAN gateway
allow-hotplug eth1
iface eth1 inet static
    address 192.168.1.1
    netmask 255.255.0.0
    # Logical Gateways
    up ip addr add 192.168.10.1/24 dev eth1   # USER GATEWAY
    up ip addr add 192.168.20.1/24 dev eth1   # SERVER GATEWAY
    up ip addr add 192.168.50.1/24 dev eth1   # OT GATEWAY
    up ip addr add 192.168.200.1/24 dev eth1  # MGMT GATEWAY
```

## 2. Firewall Rules (`/etc/nftables.conf`)

This ruleset enforces the strict isolation required by IEC 62443 and the project security policy.

```bash
#!/usr/sbin/nft -f

flush ruleset

table ip filter {
    chain input {
        type filter hook input priority 0; policy drop;
        
        # Allow loopback
        iifname "lo" accept
        
        # Allow established connections
        ct state established,related accept
        
        # Allow SSH only from Admin Network
        ip saddr 192.168.200.0/24 tcp dport 22 accept
        
        # Allow ICMP (Ping) for diagnostics
        ip protocol icmp accept
        
        # Allow DHCP requests (UDP 67/68) on LAN
        iifname "eth1" udp dport { 67, 68 } accept
    }

    chain forward {
        type filter hook forward priority 0; policy drop;
        ct state established,related accept

        # --- ACL RULES ---

        # 1. USER -> APP (Web Access only)
        # Allows Users to access the Web Server
        ip saddr 192.168.10.0/24 ip daddr 192.168.20.10 tcp dport { 80, 443 } accept

        # 2. APP -> DATA (Database Access only)
        # Allows Web Server to access Database/Redis
        ip saddr 192.168.20.10 ip daddr 192.168.20.20 tcp dport { 5432, 6379 } accept

        # 3. MGMT -> ALL (Full Administration)
        # Allows Admins to access EVERYTHING
        ip saddr 192.168.200.0/24 accept

        # 4. OT ISOLATION
        # By default, OT (192.168.50.0/24) is blocked from everything 
        # except established connections. No rules needed (Policy DROP).

        # 5. OUTBOUND INTERNET
        # Allows all internal nets to access the internet (via NAT)
        iifname "eth1" oifname "eth0" accept
    }

    chain output {
        type filter hook output priority 0; policy accept;
    }
}

table ip nat {
    chain postrouting {
        type nat hook postrouting priority 100; policy accept;
        
        # NAT (Masquerade) for Internet Access
        oifname "eth0" masquerade
    }
}
```

## 3. Applying Changes

To apply these configurations:

1.  **Networking:** `systemctl restart networking`
2.  **Firewall:** `nft -f /etc/nftables.conf`

## 4. Verification

*   **Check Routes:** `ip route`
*   **Check Rules:** `nft list ruleset`
*   **Check Logs:** `dmesg | grep nft` (if logging is enabled)
