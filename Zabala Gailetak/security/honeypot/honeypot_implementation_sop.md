# Honeypot Implementation and Configuration - SOP

## Helburua

Honeypot bat ezartzeko eta kudeatzeko prozedura, erasoen detekzioa eta analisia egiteko, batez ere OT/Industrial sektorera bideratua.

## Aurrebaldintzak

- Docker eta Docker Compose instalatuta
- SIEM sistema (ELK Stack) ezarrita
- Sareko firewall-a ezarrita
- VM edo dedicated hardware honeypot-erako

## 1. Honeypot Mota Aukeraketa

### 1.1 Low Interaction Honeypot-ak
- **Honeyd**: TCP/IP protokoloak simulatzen ditu
- **Dionaea**: Malware eta exploit-ak harrapatzen ditu
- **Cowrie**: SSH eta FTP honeypot

### 1.2 Medium/High Interaction Honeypot-ak
- **Conpot**: ICS/SCADA honeypot (Siemens S7, Modbus, etab.)
- **Kippo**: SSH honeypot
- **Glastopf**: Web application honeypot

### 1.3 Industrial Honeypot-ak
- **Conpot**: ICS protokoloak simulatzen ditu
- **SCADA-Honeynet**: SCADA sistemak simulatzen ditu

## 2. Conpot Honeypot Ezartzea (Industrial)

### 2.1 Docker bidez instalatu

```bash
docker pull honeytrap/honeytrap
docker run -d --name conpot -p 102:102 -p 502:502 -p 8080:8080 conpot/conpot
```

### 2.2 Konfigurazioa

```bash
# Conpot konfigurazio fitxategia editatu
nano /opt/conpot/templates/default/template.xml
```

### 2.3 Template-a Pertsonalizatu

```xml
<template>
    <host>
        <name>Zabala Gailetak PLC</name>
        <mac>00:0C:29:12:34:56</mac>
        <ip>192.168.50.10</ip>
    </host>
    
    <services>
        <!-- Modbus TCP -->
        <modbus port="502">
            <port>502</port>
            <unit_id>1</unit_id>
            <registers>
                <register address="40001" value="100"/>
                <register address="40002" value="200"/>
            </registers>
        </modbus>
        
        <!-- S7Comm (Siemens) -->
        <s7comm port="102">
            <port>102</port>
            <module_type>CPU 315-2 DP</module_type>
        </s7comm>
        
        <!-- HTTP Web Interface -->
        <http port="8080">
            <port>8080</port>
            <path>/</path>
            <status>200</status>
            <content>text/html</content>
            <body>
                <html>
                <body>
                <h1>Zabala Gailetak - PLC Interface</h1>
                <p>Unauthorized access prohibited</p>
                </body>
                </html>
            </body>
        </http>
    </services>
</template>
```

## 3. Cowrie SSH Honeypot Ezartzea

### 3.1 Docker Compose erabili

```yaml
version: '3'
services:
  cowrie:
    image: cowrie/cowrie
    container_name: cowrie
    ports:
      - "2222:2222" # SSH
      - "2223:2223" # Telnet
    volumes:
      - ./cowrie/cowrie.cfg:/home/cowrie/cowrie-git/cowrie/cowrie.cfg
      - ./cowrie/fs.pickle:/home/cowrie/cowrie-git/cowrie/fs.pickle
      - ./cowrie/etc:/home/cowrie/cowrie-git/cowrie/etc
      - ./cowrie/log:/home/cowrie/cowrie-git/cowrie/log
    networks:
      - honeypot-net

networks:
  honeypot-net:
    driver: bridge
```

### 3.2 Konfigurazioa

```ini
[cowrie]
# Honeypot mota
hostname = zabalagailetak-pc-01
ssh_version = SSH-2.0-OpenSSH_8.2p1 Ubuntu-4ubuntu0.3

# Listen port-ak
listen_endpoints = tcp:2222:interface=0.0.0.0

# Output format-ak
[output_json]
logfile = log/cowrie.json

# Database logging
[output_mysql]
enabled = true
host = 192.168.200.10
database = honeypot
username = cowrie
password = secure_password
```

## 4. Dionaea Malware Honeypot

### 4.1 Instalazioa

```bash
docker run -d --name dionaea -p 21:21 -p 42:42 -p 135:135 -p 445:445 -p 1433:1433 -p 3306:3306 -p 80:80 -p 443:443 -v ./dionaea:/var/dionaea honeytrap/dionaea
```

### 4.2 Konfigurazioa

```yaml
dionaea:
    listen:
        tcp:
            - address: "0.0.0.0"
              port: 21
            - address: "0.0.0.0"
              port: 445
            - address: "0.0.0.0"
              port: 3306
            - address: "0.0.0.0"
              port: 80
            - address: "0.0.0.0"
              port: 443
    
    downloads:
        dir: /var/dionaea/downloads
    
    log:
        file: /var/dionaea/log/dionaea.log
        level: info
    
    submit:
        - http://virustotal.com/api/submit
```

## 5. Sarearen Konfigurazioa

### 5.1 DMZ-a Honeypot-erako

```bash
# Firewall rules
iptables -A INPUT -p tcp --dport 102 -j DNAT --to-destination 192.168.100.50:102
iptables -A INPUT -p tcp --dport 502 -j DNAT --to-destination 192.168.100.50:502
iptables -A INPUT -p tcp --dport 2222 -j DNAT --to-destination 192.168.100.51:2222
```

### 5.2 NAT eta Port Forwarding

```bash
# External IP -> Honeypot
iptables -t nat -A PREROUTING -d EXTERNAL_IP -p tcp --dport 22 -j DNAT --to 192.168.100.51:2222
```

## 6. Logging eta SIEM Integrazioa

### 6.1 Logstash Konfigurazioa

```ruby
input {
  file {
    path => "/var/log/honeypot/*.log"
    type => "honeypot"
    start_position => "beginning"
  }
}

filter {
  if [type] == "honeypot" {
    grok {
      match => {
        "message" => "%{TIMESTAMP_ISO8601:timestamp} %{LOGLEVEL:level} %{GREEDYDATA:msg}"
      }
    }
    
    # IP eta geo location atera
    geoip {
      source => "[src_ip]"
    }
  }
}

output {
  elasticsearch {
    hosts => ["http://elasticsearch:9200"]
    index => "honeypot-%{+YYYY.MM.dd}"
  }
}
```

### 6.2 Kibana Dashboard-a Sortu

- IP helbideen mapa
- Eraso moten diagramak
- Geolokalizazioa
- Time-series grafikoak
- Top erasotzaileak

## 7. Alertak

### 7.1 Threshold Alert-ak

```python
# honeypot_monitor.py
import elasticsearch

es = elasticsearch.Elasticsearch(['http://elasticsearch:9200'])

def check_threshold():
    query = {
        "query": {
            "range": {
                "@timestamp": {
                    "gte": "now-1h"
                }
            }
        }
    }
    
    result = es.search(index="honeypot-*", body=query)
    
    if result['hits']['total']['value'] > 100:
        send_alert("High attack volume detected on honeypot")
    
    # Check for specific patterns
    # SQL injection attempts
    # Brute force attacks
    # Industrial protocol scans
```

### 7.2 Slack Notification

```python
import requests

def send_slack_alert(message):
    webhook_url = "SLACK_WEBHOOK_URL"
    payload = {"text": message}
    requests.post(webhook_url, json=payload)
```

## 8. Analisi eta Reporting

### 8.1 Eguneroko Report-a

```python
# daily_report.py
def generate_daily_report():
    # Count attacks by type
    # Top source IPs
    # Geolocation distribution
    # Malware samples collected
    
    report = {
        "date": datetime.now().strftime("%Y-%m-%d"),
        "total_attacks": count_total_attacks(),
        "attack_types": count_by_type(),
        "top_sources": get_top_sources(),
        "malware": get_malware_samples()
    }
    
    save_report(report)
```

### 8.2 Weekly Analysis

- Eragin analisia
- Tren-ak identifikatu
- Zero-day detekzioak
- Inteligentsia kanpoetara partekatu

## 9. Kudeaketa eta Mantentzea

### 9.1 Log-ak Rotatu

```bash
# Log rotation konfigurazioa
/var/log/honeypot/*.log {
    daily
    rotate 30
    compress
    delaycompress
    missingok
    notifempty
    create 0640 honeypot honeypot
}
```

### 9.2 Backup-ak Egin

```bash
# Honeypot konfigurazioak backup
tar -czf honeypot-backup-$(date +%Y%m%d).tar.gz /opt/honeypot/
```

## 10. Segurtasun Neurriak

### 10.1 Honeypot-aren Isolazioa

- Sare isolatu batean jarri
- Firewall rules zorrotzak
- Ez erabili ekoizpeneko sarean
- Ez partekatu kredentzialak

### 10.2 Legala Etika

- Honeypot-aren erabilpena dokumentatu
- Erabilera politikak definitu
- Pribatutasun legeak kontuan hartu
- Consent eta notifikazioak

## 11. Performance Monitoring

### 11.1 Resource Usage

```bash
# CPU, Memory, Network monitoring
docker stats conpot cowrie dionaea
```

### 11.2 Auto-scaling

```bash
# Trazimendua handia bada, honeypot gehiago gehitu
```

## 12. Dissemination eta Inteligentsia

### 12.1 Threat Inteligentsia Partekatu

- MISP erabili
- TAXII erabili
- CERT-ak informatu
- Community partekatu

### 12.2 Learnings Aplikatu

- Honeypot-en ikasitakoak ekoizpenean aplikatu
- Firewall rules eguneratu
- Signature-ak gehitu
- IPS rules eguneratu

## Erreferentziak

- Honeynet Project: https://www.honeynet.org/
- Conpot Documentation: https://conpot.readthedocs.io/
- Cowrie Documentation: https://cowrie.readthedocs.io/
- Dionaea Documentation: https://dionaea.readthedocs.io/
- MISP Project: https://www.misp-project.org/