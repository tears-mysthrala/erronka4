# Patch Management SOP - Zabala Gailetak

## 1. Vulnerability Scanning
- Weekly scans using OpenVAS
- Critical findings reported immediately

## 2. Patch Classification
| Severity | CVSS | Response Time |
|----------|------|---------------|
| Critical | 9.0-10.0 | 72 hours |
| High | 7.0-8.9 | 7 days |
| Medium | 4.0-6.9 | 30 days |
| Low | 0.1-3.9 | 90 days |

## 3. Deployment Process
1. Test patches in development
2. Deploy to staging
3. Monitor for issues
4. Deploy to production
5. Document changes
