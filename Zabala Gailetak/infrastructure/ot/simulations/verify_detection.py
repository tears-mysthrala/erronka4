#!/usr/bin/env python3
"""
Verifica que SIEM detectó los ataques simulados
Consulta Elasticsearch para alertas esperadas
"""
from elasticsearch import Elasticsearch
from datetime import datetime, timedelta
import sys

def verify_siem_detection():
    print("[+] Verificando detección en SIEM...")

    # Conectar a Elasticsearch
    try:
        es = Elasticsearch(['http://localhost:9200'], request_timeout=10)
        if not es.ping():
            print("[!] No se pudo conectar a Elasticsearch")
            print("[*] Nota: Asegúrate de que SIEM está activo")
            return False
    except Exception as e:
        print(f"[!] Error conectando a Elasticsearch: {e}")
        return False

    # Buscar alertas en los últimos 15 minutos
    time_from = datetime.now() - timedelta(minutes=15)

    expected_alerts = [
        'AUTH-001',  # Brute force
        'SCAN-001',  # Security scanner
        'RATE-001',  # Rate limiting
    ]

    found_alerts = []

    for alert_id in expected_alerts:
        query = {
            "query": {
                "bool": {
                    "must": [
                        {"match": {"alert.id": alert_id}},
                        {"range": {"@timestamp": {"gte": time_from.isoformat()}}}
                    ]
                }
            }
        }

        try:
            result = es.search(index="zabala-alerts-*", body=query)
            hits = result['hits']['total']['value']

            if hits > 0:
                print(f"    [✓] {alert_id}: {hits} alertas detectadas")
                found_alerts.append(alert_id)
            else:
                print(f"    [!] {alert_id}: No detectado")
        except Exception as e:
            print(f"    [!] Error buscando {alert_id}: {e}")

    detection_rate = (len(found_alerts) / len(expected_alerts)) * 100
    print(f"\n[+] Tasa de detección: {detection_rate:.1f}%")

    if detection_rate >= 66:  # 2 de 3 alertas
        print("[✓] SIEM funcionando correctamente")
        return True
    else:
        print("[!] SIEM no detectó suficientes ataques")
        return False

if __name__ == "__main__":
    success = verify_siem_detection()
    sys.exit(0 if success else 1)
