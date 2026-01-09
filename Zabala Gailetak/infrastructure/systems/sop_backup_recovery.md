# Backup & Recovery SOP - Zabala Gailetak

## 1. Backup Schedule
| Type | Frequency | Retention |
|------|-----------|-----------|
| Full | Weekly (Sunday 2:00 AM) | 12 months |
| Incremental | Daily (2:00 AM) | 30 days |
| Transaction logs | Every 15 minutes | 7 days |

## 2. Backup Procedures
1. Verify sufficient disk space
2. Stop non-essential services
3. Create snapshots
4. Transfer to backup server
5. Verify checksums

## 3. Recovery Procedures
1. Assess damage and select restore point
2. Notify stakeholders
3. Restore from backup
4. Verify data integrity
5. Resume operations

## 4. Testing Schedule
- Weekly: Verify backup completion
- Monthly: Test restoration in dev environment
- Quarterly: Full DR drill
