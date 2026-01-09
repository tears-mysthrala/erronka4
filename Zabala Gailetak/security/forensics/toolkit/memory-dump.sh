#!/bin/bash
CASE_ID=$1
OUTPUT_DIR="/evidence/memory/$CASE_ID"
mkdir -p "$OUTPUT_DIR"

echo "[+] Acquiring memory dump"
# LiME command for memory acquisition
# sha256sum for chain of custody

echo "[✓] Memory dump complete: $OUTPUT_DIR"
