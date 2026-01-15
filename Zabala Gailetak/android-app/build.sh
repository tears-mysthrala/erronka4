#!/bin/bash
# Build script con Java 21 configurado automáticamente

set -e

# Usar Java 21
export JAVA_HOME=/home/kalista/.local/share/mise/installs/java/21
export PATH=$JAVA_HOME/bin:$PATH

echo "Java: $(java -version 2>&1 | head -1)"

# Limpiar si se pide
if [ "$1" = "clean" ]; then
    rm -rf .gradle build app/build
fi

# Build
if [ "$1" = "release" ]; then
    ./gradlew assembleRelease --no-daemon
else
    ./gradlew assembleDebug --no-daemon
fi
