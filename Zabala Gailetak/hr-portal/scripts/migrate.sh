#!/bin/bash

# ============================================================================
# Database Migration Script for HR Portal
# ============================================================================

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}======================================${NC}"
echo -e "${GREEN}HR Portal Database Migration${NC}"
echo -e "${GREEN}======================================${NC}"

# Load environment variables
if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
else
    echo -e "${RED}Error: .env file not found${NC}"
    exit 1
fi

# Database connection parameters
DB_HOST=${DB_HOST:-localhost}
DB_PORT=${DB_PORT:-5432}
DB_NAME=${DB_NAME:-hr_portal}
DB_USER=${DB_USER:-hr_user}

MIGRATIONS_DIR="./migrations"

echo -e "${YELLOW}Database: ${DB_NAME}@${DB_HOST}:${DB_PORT}${NC}"
echo ""

# Check if PostgreSQL is available
if ! command -v psql &> /dev/null; then
    echo -e "${RED}Error: psql command not found. Please install PostgreSQL client.${NC}"
    exit 1
fi

# Test database connection
echo -e "${YELLOW}Testing database connection...${NC}"
if PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d postgres -c '\q' 2>/dev/null; then
    echo -e "${GREEN}✓ Database connection successful${NC}"
else
    echo -e "${RED}✗ Database connection failed${NC}"
    exit 1
fi

# Create database if it doesn't exist
echo -e "${YELLOW}Checking if database exists...${NC}"
DB_EXISTS=$(PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d postgres -tAc "SELECT 1 FROM pg_database WHERE datname='$DB_NAME'")
if [ "$DB_EXISTS" != "1" ]; then
    echo -e "${YELLOW}Creating database $DB_NAME...${NC}"
    PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d postgres -c "CREATE DATABASE $DB_NAME;"
    echo -e "${GREEN}✓ Database created${NC}"
else
    echo -e "${GREEN}✓ Database already exists${NC}"
fi

# Run migrations
echo ""
echo -e "${YELLOW}Running migrations...${NC}"

if [ ! -d "$MIGRATIONS_DIR" ]; then
    echo -e "${RED}Error: Migrations directory not found: $MIGRATIONS_DIR${NC}"
    exit 1
fi

MIGRATION_COUNT=0
for migration in $(ls -1 $MIGRATIONS_DIR/*.sql | sort); do
    MIGRATION_NAME=$(basename $migration)
    echo -e "${YELLOW}Applying migration: $MIGRATION_NAME${NC}"
    
    if PGPASSWORD=$DB_PASSWORD psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d $DB_NAME -f $migration; then
        echo -e "${GREEN}✓ Migration applied: $MIGRATION_NAME${NC}"
        ((MIGRATION_COUNT++))
    else
        echo -e "${RED}✗ Migration failed: $MIGRATION_NAME${NC}"
        exit 1
    fi
done

echo ""
echo -e "${GREEN}======================================${NC}"
echo -e "${GREEN}Migration completed successfully!${NC}"
echo -e "${GREEN}Total migrations applied: $MIGRATION_COUNT${NC}"
echo -e "${GREEN}======================================${NC}"
