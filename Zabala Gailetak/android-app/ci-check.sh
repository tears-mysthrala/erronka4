#!/bin/bash
# CI Check Script for Android App
# Run this locally to verify the build before pushing

set -e

echo "=== Android CI Build Verification ==="
echo ""

# Check Java version
echo "=== Java Version ==="
java -version
echo ""

# Clean build
echo "=== Clean Build ==="
./gradlew clean --no-daemon
echo ""

# Build debug APK
echo "=== Build Debug APK ==="
./gradlew assembleDebug --no-daemon --stacktrace
echo ""

# Build release APK
echo "=== Build Release APK ==="
./gradlew assembleRelease --no-daemon --stacktrace
echo ""

# Run lint
echo "=== Run Lint ==="
./gradlew lint --no-daemon || true
echo ""

# Check APK outputs
echo "=== APK Outputs ==="
find app/build/outputs/apk -name "*.apk" -type f -exec ls -lh {} \;
echo ""

echo "=== Build Verification Complete ==="
echo "Debug APK: $(find app/build/outputs/apk/debug -name "*.apk" 2>/dev/null | wc -l) found"
echo "Release APK: $(find app/build/outputs/apk/release -name "*.apk" 2>/dev/null | wc -l) found"
