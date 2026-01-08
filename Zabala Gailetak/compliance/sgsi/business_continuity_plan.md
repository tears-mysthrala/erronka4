# Business Continuity Plan
## Zabala Gailetak S.A.

**Document ID:** BCP-001  
**Version:** 1.0  
**Date:** January 8, 2026  
**Classification:** Highly Confidential  
**Owner:** Chief Executive Officer (CEO)  
**BCP Coordinator:** Chief Information Security Officer (CISO)  
**Review Frequency:** Annual (and after major incidents)  
**Next Review Date:** January 8, 2027

---

## 1. Document Control

### 1.1 Version History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-01-08 | CISO | Initial BCP creation |

### 1.2 Approval

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Chief Executive Officer | [Name] | | |
| Chief Financial Officer | [Name] | | |
| Chief Information Security Officer | [Name] | | |
| Operations Manager | [Name] | | |

### 1.3 Distribution and Access

**Authorized Personnel:**
- Executive Management Team
- Business Continuity Team members
- Department Heads
- Emergency Response Team

**Storage Locations:**
- Primary: Secure document management system (encrypted, access-controlled)
- Backup: Printed copies in emergency kits at each facility
- Off-site: Cloud storage accessible during facility unavailability
- Emergency Contacts: Mobile-accessible version for key personnel

---

## 2. Executive Summary

This Business Continuity Plan (BCP) enables Zabala Gailetak to continue critical business functions during and after disruptive events. The plan addresses both IT and OT systems, ensuring cookie production, customer service, and essential operations can resume within defined timeframes.

**Key Objectives:**
- Protect employee safety
- Maintain customer service and production capacity
- Minimize financial and reputational damage
- Ensure compliance with legal and contractual obligations
- Resume normal operations within 24-72 hours depending on disruption severity

**Critical Success Factors:**
- Maximum Tolerable Period of Disruption (MTPD): 24 hours for critical processes
- Recovery Time Objective (RTO): 4 hours for IT systems, 8 hours for production
- Recovery Point Objective (RPO): 1 hour for data loss
- Minimum Business Continuity Objective (MBCO): 60% production capacity within 8 hours

---

## 3. Scope and Assumptions

### 3.1 Scope

This BCP covers:
- **Critical Business Processes:** Cookie production, order fulfillment, customer service, quality control
- **IT Systems:** Web application, databases, email, network infrastructure
- **OT Systems:** PLCs, SCADA, production equipment, packaging lines
- **Facilities:** Main production facility in [City], administrative offices, warehouse
- **Personnel:** All employees, contractors, and key suppliers

### 3.2 Disruption Scenarios

This plan addresses:
1. **Cyber Incidents:** Ransomware, DDoS attacks, data breaches
2. **IT/OT System Failures:** Hardware failure, software corruption, network outage
3. **Natural Disasters:** Fire, flood, earthquake, severe weather
4. **Pandemics:** Disease outbreak affecting workforce
5. **Utility Disruptions:** Power outage, water supply interruption, telecommunications failure
6. **Supply Chain Disruption:** Key supplier failure, transportation disruption
7. **Physical Security:** Terrorism, vandalism, theft
8. **Personnel Issues:** Key person unavailability, labor strikes

### 3.3 Assumptions

- Emergency services (fire, police, medical) are available
- Insurance coverage is maintained and adequate
- Business continuity budget is approved annually
- Personnel are trained and participate in drills
- Critical suppliers have their own BCP plans
- Off-site backup location is accessible within 2 hours
- Communication systems (mobile phones, internet) are available or can be restored quickly

---

## 4. Business Impact Analysis (BIA)

### 4.1 Critical Business Functions

| Function | Description | MTPD | RTO | RPO | Impact if Disrupted |
|----------|-------------|------|-----|-----|---------------------|
| **Cookie Production** | Manufacturing operations (mixing, baking, packaging) | 24h | 8h | 4h | Revenue loss €20K/day, contract penalties, reputation damage |
| **Order Management** | Order entry, processing, invoicing | 12h | 4h | 1h | Customer dissatisfaction, revenue loss €10K/day |
| **Inventory Management** | Raw material and finished goods tracking | 24h | 8h | 4h | Production delays, stock inaccuracies, waste |
| **Quality Control** | Product testing and compliance | 24h | 8h | 4h | Regulatory violations, product recalls, safety risks |
| **Customer Service** | Support, complaints, inquiries | 24h | 4h | N/A | Customer dissatisfaction, lost sales |
| **Financial Operations** | Payroll, accounts payable/receivable | 48h | 12h | 24h | Payment delays, supplier issues, employee morale |
| **IT Infrastructure** | Servers, network, databases | 8h | 4h | 1h | All systems dependent, critical enabler |
| **OT Infrastructure** | PLCs, SCADA, production control | 8h | 4h | 1h | Production stoppage, safety risks |

**MTPD:** Maximum Tolerable Period of Disruption  
**RTO:** Recovery Time Objective (how quickly function must be restored)  
**RPO:** Recovery Point Objective (maximum acceptable data loss)

### 4.2 Critical Resources

**IT Systems (Priority 1):**
- Web application server (order management)
- Database server (PostgreSQL/MongoDB)
- Email server
- File server
- Firewall and network infrastructure
- VPN access for remote work

**OT Systems (Priority 1):**
- Production PLCs (mixing, baking, packaging lines)
- SCADA system
- Temperature and humidity control systems
- Quality control lab equipment

**Facilities (Priority 1):**
- Main production facility
- Power supply (3-phase electrical)
- Water supply (production and sanitation)
- Cooling/HVAC systems

**Personnel (Priority 1):**
- Production Manager and shift supervisors
- Maintenance technicians
- Quality control manager
- IT Administrator
- CEO and CFO

**Suppliers (Priority 1):**
- Flour supplier (2 primary vendors)
- Packaging materials supplier
- Utilities (electricity, water, gas)

### 4.3 Financial Impact Assessment

| Disruption Duration | Production Loss | Revenue Impact | Indirect Costs | Total Impact |
|---------------------|-----------------|----------------|----------------|--------------|
| 4 hours | 16% | €3,000 | €1,000 | €4,000 |
| 8 hours | 33% | €6,000 | €3,000 | €9,000 |
| 24 hours | 100% | €20,000 | €10,000 | €30,000 |
| 3 days | 300% | €60,000 | €40,000 | €100,000 |
| 1 week | 700% | €140,000 | €100,000 | €240,000 |

**Indirect Costs:** Contract penalties, overtime, emergency procurement, reputation damage, customer churn

---

## 5. Business Continuity Strategy

### 5.1 Overall Strategy

Zabala Gailetak employs a multi-layered continuity strategy:

1. **Prevention:** Minimize likelihood of disruptions through risk controls
2. **Mitigation:** Reduce impact through redundancy and resilience
3. **Response:** Activate continuity procedures when disruption occurs
4. **Recovery:** Restore normal operations systematically
5. **Improvement:** Learn from incidents and update plans

### 5.2 IT System Continuity Strategy

**Infrastructure Redundancy:**
- Cloud-hosted critical systems (AWS/Azure multi-region)
- On-premises backup servers (hot standby for critical systems)
- Redundant internet connections (2 ISPs, fiber + 4G failover)
- Uninterruptible Power Supply (UPS) for 30 minutes + generator
- Daily automated backups (see Backup Strategy section)

**Disaster Recovery Site:**
- Cloud-based DR infrastructure (IaaS)
- RTO: 4 hours (bring critical systems online)
- RPO: 1 hour (maximum data loss)
- Testing: Quarterly DR drills

**Remote Work Capability:**
- VPN access for all office staff (AnyConnect or OpenVPN)
- Cloud-based collaboration tools (Microsoft 365 or Google Workspace)
- Company laptops with full disk encryption
- Remote access security (MFA, endpoint protection)

### 5.3 OT System Continuity Strategy

**Equipment Redundancy:**
- Critical spare parts inventory (PLCs, sensors, actuators)
- Backup PLC configurations stored securely off-site
- Manual operation procedures for critical equipment
- Preventive maintenance program to reduce failures

**Alternative Production:**
- Agreements with 2 partner manufacturers for emergency production (MOU signed)
- Capacity: 40% of normal production within 24 hours
- Quality standards verified and approved
- Confidentiality and security requirements in contracts

**Phased Production Recovery:**
1. **Phase 1 (0-4h):** Assessment, safety checks, damage control
2. **Phase 2 (4-8h):** Restore utilities, critical equipment repair, manual operations if needed
3. **Phase 3 (8-24h):** Resume automated production at 60% capacity
4. **Phase 4 (24-72h):** Full capacity restoration

### 5.4 Facility Continuity Strategy

**Alternative Work Locations:**
- Office staff: Remote work from home (fully enabled)
- Production staff: Partner manufacturing facilities (agreements in place)
- Emergency command center: Local hotel conference room (pre-arranged)

**Facility Protection:**
- Fire suppression system (FM-200 for IT, sprinklers for production)
- Water leak detection and automatic shutoff valves
- Backup generator (diesel, 1000 kVA, 72-hour fuel capacity)
- Climate control backup (portable HVAC units for critical areas)

### 5.5 Supply Chain Continuity Strategy

**Supplier Diversification:**
- Minimum 2 suppliers for critical raw materials
- 30-day inventory buffer for key ingredients
- Supplier BCP requirements in contracts
- Regular supplier risk assessments

**Logistics Alternatives:**
- 3 transportation providers under contract
- Warehouse capacity at 3rd party logistics provider
- Cross-docking capability for rapid shipment

### 5.6 Communication Strategy

**Internal Communication:**
- Emergency notification system (SMS/email blast to all employees)
- Backup contact list (personal mobile phones)
- Intranet status page for updates
- Daily briefings during incident (in-person or virtual)

**External Communication:**
- Customer notification within 2 hours of significant disruption
- Supplier and partner notification as needed
- Media relations (CEO or designated spokesperson only)
- Regulatory notification if required (AEPD for data breaches, food safety authorities)

---

## 6. Roles and Responsibilities

### 6.1 Business Continuity Team

**BCP Coordinator (CISO):**
- Overall BCP maintenance and testing
- Activate BCP when disruption occurs
- Coordinate between response teams
- Communication hub for incident updates
- Post-incident review and plan updates

**Emergency Response Team:**

| Role | Primary | Backup | Responsibilities |
|------|---------|--------|------------------|
| **Incident Commander** | CEO | CFO | Overall authority, resource allocation, executive decisions |
| **Operations Lead** | Operations Manager | Production Supervisor | Production recovery, facility assessment, manual operations |
| **IT Recovery Lead** | IT Manager | Senior System Admin | IT system recovery, network restoration, data recovery |
| **OT Recovery Lead** | Maintenance Manager | Senior Technician | OT system recovery, equipment repair, safety checks |
| **Communications Lead** | HR Manager | Marketing Manager | Employee communication, customer updates, media relations |
| **Logistics Lead** | Warehouse Manager | Supply Chain Manager | Supplier coordination, transportation, alternative sourcing |
| **Safety Officer** | Health & Safety Manager | Quality Manager | Personnel safety, evacuation, regulatory compliance |
| **Finance Lead** | CFO | Controller | Emergency funding, insurance claims, financial tracking |
| **Legal Advisor** | Legal Counsel | External Law Firm | Contractual obligations, liability, regulatory compliance |

### 6.2 Authority and Decision-Making

**Decision Hierarchy:**
1. **Incident Commander (CEO):** Final authority on all major decisions
2. **BCP Coordinator (CISO):** Operational authority to activate procedures
3. **Function Leads:** Authority within their domain (IT, OT, Operations)

**Emergency Authorization:**
- Up to €50,000: BCP Coordinator approval
- €50,000-€200,000: Incident Commander approval
- >€200,000: Incident Commander + CFO approval

**After-Hours Contact:**
- Emergency phone tree (primary and backup contacts)
- 24/7 security hotline: +34 XXX XXX XXX
- Incident Commander reachable within 30 minutes

---

## 7. Activation Procedures

### 7.1 Activation Criteria

BCP is activated when:
- Critical system failure lasting >30 minutes
- Facility unavailable or unsafe
- Cyber incident affecting critical systems
- Natural disaster impacting operations
- Key personnel unavailable (>50% of critical roles)
- Regulatory order to cease operations
- Any event threatening MTPD thresholds

### 7.2 Activation Process

**Step 1: Detection and Notification (0-15 minutes)**
1. Any employee detecting disruption reports to:
   - Direct manager
   - Security hotline: +34 XXX XXX XXX
   - Email: emergency@zabalagailetak.com
2. Security/IT team assesses severity
3. If meets activation criteria, notify BCP Coordinator

**Step 2: Initial Assessment (15-30 minutes)**
1. BCP Coordinator confirms activation decision
2. Notify Incident Commander (CEO)
3. Gather Emergency Response Team (physical or virtual)
4. Conduct rapid impact assessment:
   - What is affected?
   - Cause and likely duration?
   - Safety concerns?
   - Which critical functions are impacted?

**Step 3: BCP Activation Declaration (30-60 minutes)**
1. Incident Commander formally declares BCP activation
2. Establish Command Center:
   - Primary: Main office conference room
   - Backup: Virtual (Zoom/Teams call)
   - Tertiary: Hotel conference room (pre-arranged)
3. Notify all employees via emergency alert system
4. Brief Emergency Response Team on:
   - Situation summary
   - Assigned roles and responsibilities
   - Initial priorities and actions
   - Communication protocols

**Step 4: Execute Recovery Procedures (1+ hours)**
- Function leads execute recovery procedures (see Section 8)
- Regular status updates to Incident Commander (hourly initially)
- Adjust strategy based on evolving situation

---

## 8. Recovery Procedures

### 8.1 IT System Recovery

**Critical Systems Priority Order:**
1. Network infrastructure and firewalls
2. Authentication systems (Active Directory/LDAP)
3. Database servers
4. Web application (order management)
5. Email server
6. File server
7. VPN for remote access

**Recovery Steps:**

**For Cloud-Hosted Systems (AWS/Azure):**
1. Verify cloud provider status (AWS/Azure status page)
2. If regional failure, failover to secondary region:
   ```bash
   aws route53 change-resource-record-sets --hosted-zone-id ZXXXXX --change-batch file://failover-dns.json
   ```
3. Verify database replication status
4. Test application connectivity and functionality
5. Update DNS to point to DR environment (TTL: 300s)
6. Monitor performance and errors

**For On-Premises Systems:**
1. Assess damage:
   - Hardware failure? (replace or fail over to backup server)
   - Software corruption? (restore from backup)
   - Network issue? (switch to backup ISP, 4G failover)
2. If hardware replacement needed:
   - Retrieve spare server from inventory
   - Install from backup image or boot from SAN
   - Restore latest backup (see Backup Recovery procedure)
3. If data center unavailable:
   - Activate cloud-based DR environment
   - Restore backups to cloud instances
   - Reconfigure applications for cloud networking

**Backup Recovery Procedure:**
1. Identify last clean backup (check backup logs):
   ```bash
   ls -lh /backup/mongodb/ | grep $(date +%Y-%m-%d)
   ```
2. Verify backup integrity (checksum):
   ```bash
   sha256sum -c backup-20260108.tar.gz.sha256
   ```
3. Restore database:
   ```bash
   mongorestore --host localhost --port 27017 --gzip --archive=/backup/mongodb/backup-20260108.tar.gz
   ```
4. Restore application files:
   ```bash
   rsync -avz /backup/application/ /var/www/zabalagailetak/
   ```
5. Verify data integrity (spot checks on recent orders, user accounts)
6. Test critical functions (login, order creation, reporting)

**Estimated Recovery Times:**
- Cloud failover: 30-60 minutes
- Backup restoration: 2-4 hours
- Full DR site activation: 4-6 hours

### 8.2 OT System Recovery

**Critical OT Systems Priority Order:**
1. Safety systems (emergency stops, fire suppression)
2. Main production PLC (mixing and baking)
3. Packaging line PLC
4. SCADA monitoring system
5. Temperature/humidity control
6. Inventory tracking

**Recovery Steps:**

**For PLC Failure:**
1. **Safety First:**
   - Ensure all production lines are safely stopped
   - Lock out/tag out electrical supply
   - Clear personnel from affected areas
2. **Diagnosis:**
   - Check error codes on PLC display
   - Verify power supply and connections
   - Test I/O modules with multimeter
3. **Recovery Options:**
   - **Option A (Minor):** Reboot PLC, reload program from backup
   - **Option B (Hardware Failure):** Replace PLC from spare inventory
   - **Option C (Major Failure):** Switch to manual operation procedures
4. **PLC Replacement Procedure:**
   - Install spare PLC (exact model in inventory)
   - Load backup configuration from USB drive:
     - Location: `/backup/plc-configs/` (dated backups)
     - Verify configuration matches current production recipes
   - Test in simulation mode before connecting to equipment
   - Connect I/O and verify all sensor readings
   - Run test cycle without product
   - Gradual restart with quality control checks
5. **Manual Operation (if PLC unavailable):**
   - Activate manual control panels
   - Follow manual operation checklist (see Appendix C)
   - Production rate: 30% of normal
   - Enhanced quality monitoring (every 15 minutes)

**For SCADA System Failure:**
1. Verify PLC control still functional (production can continue without SCADA monitoring)
2. Restore SCADA server from backup or fail over to hot standby
3. Temporary monitoring: Direct PLC interface at control panels
4. Resume normal SCADA operations once restored

**For Complete Facility Loss:**
1. Activate partner manufacturing agreements
2. Transfer production recipes and specifications (encrypted USB drives in emergency kit)
3. Deploy quality control manager to partner facility
4. Coordinate raw material delivery to partner facility
5. Estimated timeline: 24 hours to first production at partner facility

**Estimated Recovery Times:**
- PLC reboot/reconfiguration: 1-2 hours
- PLC replacement: 4-6 hours
- SCADA restoration: 2-4 hours
- Manual operations activation: 30 minutes
- Partner facility production start: 24 hours

### 8.3 Production Operations Recovery

**Phase 1: Immediate Response (0-4 hours)**

**Actions:**
1. **Personnel Safety:**
   - Account for all employees (emergency muster points)
   - Provide first aid if needed
   - Evacuate if necessary (fire, gas leak, structural damage)
2. **Damage Assessment:**
   - Visual inspection by Safety Officer and Operations Lead
   - Document with photos/video
   - Identify immediate hazards (exposed wiring, gas leaks, structural damage)
3. **Secure Facility:**
   - Shut off utilities if hazardous
   - Lock entrances to damaged areas
   - Station security if needed
4. **Customer Notification:**
   - If production >4 hours delayed, notify customers within 2 hours
   - Provide estimated recovery timeline
   - Offer alternatives if available

**Phase 2: Stabilization (4-8 hours)**

**Actions:**
1. **Utilities Restoration:**
   - Coordinate with utility providers
   - Activate backup generator if power unavailable
   - Verify water quality before resuming production
2. **Critical Equipment Prioritization:**
   - Focus on highest priority production line first
   - Test equipment operation without product (dry run)
   - Quality control equipment must be operational before production resumes
3. **Personnel Mobilization:**
   - Recall essential production staff
   - Brief on situation and safety procedures
   - Confirm transportation access to facility
4. **Supplier Coordination:**
   - Verify raw material availability
   - Reschedule deliveries if needed
   - Expedite critical ingredients if depleted

**Phase 3: Production Resumption (8-24 hours)**

**Actions:**
1. **Partial Production Start:**
   - Single production line initially (highest volume product)
   - Enhanced quality checks (double frequency)
   - Start with small batch to verify quality
2. **Inventory Management:**
   - Verify finished goods inventory (intact and quality maintained)
   - Prioritize orders: contractually obligated > highest value > longest relationship
   - Adjust production schedule for catch-up
3. **Communication Updates:**
   - Update customers on recovery progress
   - Internal status briefings (shift start and end)
   - Notify suppliers of revised needs

**Phase 4: Full Restoration (24-72 hours)**

**Actions:**
1. **Scale-Up Production:**
   - Activate additional production lines
   - Extended shifts or overtime if needed
   - Catch-up production for delayed orders
2. **Return to Normal Operations:**
   - Resume normal quality control frequency
   - Standard production scheduling
   - Deactivate emergency procedures
3. **Financial Recovery:**
   - File insurance claim (with documentation from Phase 1)
   - Calculate financial impact
   - Invoice customers for delivered orders

### 8.4 Facility Recovery (Extended Outage)

**If Primary Facility Unavailable >72 hours:**

**Immediate Actions (0-24 hours):**
1. Activate partner manufacturing agreements (see Section 8.2)
2. Establish temporary office space:
   - Remote work for all office staff
   - Essential meetings at hotel conference center
   - Emergency documents accessible via cloud storage
3. Notify all stakeholders (customers, suppliers, employees, insurance)

**Short-Term Recovery (1-4 weeks):**
1. Lease temporary production space if partner capacity insufficient
2. Relocate essential equipment if salvageable
3. Rebuild critical infrastructure first (IT, office, QC lab)
4. Hire temporary staff if needed

**Long-Term Recovery (1-6 months):**
1. Full facility reconstruction or relocation
2. Upgrade systems based on lessons learned
3. Gradual return to primary facility
4. Deactivate temporary arrangements

---

## 9. Communication Plan

### 9.1 Internal Communication

**Emergency Notification System:**
- Primary: Automated SMS/email to all employees
- Backup: Phone tree (managers call their teams)
- Message template:
  ```
  ZABALA GAILETAK EMERGENCY ALERT
  Date/Time: [AUTO]
  Incident: [Brief description]
  Status: [Safe/Evacuate/Remote Work/Standby]
  Next Update: [Time]
  Contact: [BCP Coordinator number]
  ```

**Status Updates:**
- During incident: Every 2 hours minimum
- After resolution: Daily updates until normal operations
- Channel: Email + intranet status page + SMS for critical updates

**Employee Briefings:**
- Daily in-person or virtual meetings during incident
- Key information: situation summary, safety instructions, work expectations, next steps

### 9.2 External Communication

**Customer Communication:**
- **Notification Threshold:** Any disruption affecting orders >4 hours
- **Timing:** Within 2 hours of incident
- **Method:** Email to account contacts, phone calls for major customers
- **Message Content:**
  - What happened (high-level, no sensitive details)
  - Impact on their orders
  - Estimated recovery timeline
  - Mitigation actions (partner production, expedited shipping)
  - Contact for questions
- **Follow-Up:** Daily updates until orders fulfilled

**Supplier Communication:**
- Notify of changed delivery schedules or quantities
- Request expedited delivery if needed for recovery
- Coordinate alternative delivery locations if facility unavailable

**Regulatory Communication:**
- **AEPD (Data Protection Authority):** Within 72 hours for personal data breaches
- **Food Safety Authority:** Immediately if product safety affected
- **Labor Authority:** If workplace accident or major layoff

**Media Relations:**
- **Spokesperson:** CEO only (backup: CFO)
- **No Comment Policy:** Employees refer all media inquiries to CEO
- **Prepared Statement:** Draft within 4 hours of major incident
- **Proactive vs. Reactive:** Consider proactive press release for major incidents to control narrative

**Insurance Communication:**
- Notify insurance broker within 24 hours
- Document all damage with photos, video, inventory lists
- Track all expenses for claim (keep receipts)
- Coordinate with adjuster for site visit

---

## 10. Testing and Maintenance

### 10.1 Testing Schedule

| Test Type | Frequency | Participants | Duration | Objectives |
|-----------|-----------|--------------|----------|------------|
| **Tabletop Exercise** | Quarterly | Emergency Response Team | 2 hours | Walk through BCP scenarios, discuss roles and decisions |
| **IT DR Test** | Quarterly | IT team | 4 hours | Restore backups, failover to DR site, verify RTO/RPO |
| **OT Manual Operation Drill** | Semi-Annual | Production team | 2 hours | Practice manual operation of critical equipment |
| **Full-Scale Exercise** | Annual | All employees | 4-8 hours | Simulate major disruption, activate all BCP procedures |
| **Communication Test** | Monthly | HR/Communications | 30 min | Test emergency notification system, verify contact info |
| **Backup Restoration Test** | Monthly | IT team | 2 hours | Restore a random backup, verify data integrity |

### 10.2 Tabletop Exercise Scenarios

**Scenario 1: Ransomware Attack**
- Situation: Monday morning, all servers encrypted, ransom note demanding 10 BTC
- Discussion Points: Isolate infection, restore from backups, law enforcement notification, customer communication, prevention improvements

**Scenario 2: Facility Fire**
- Situation: Friday night fire in production area, sprinklers activated, estimated 2 weeks downtime
- Discussion Points: Personnel safety, damage assessment, partner manufacturing activation, insurance claim, customer impact

**Scenario 3: Key Personnel Unavailability**
- Situation: IT Manager and backup both unavailable (car accident), critical system failure during their absence
- Discussion Points: Succession planning, documentation accessibility, emergency contractor engagement, cross-training needs

**Scenario 4: Supply Chain Disruption**
- Situation: Primary flour supplier bankrupt, immediate cessation of deliveries, 10-day inventory on hand
- Discussion Points: Activate secondary supplier, expedited procurement, recipe adjustments, production prioritization

### 10.3 Full-Scale Exercise

**Annual Exercise (Example: November):**

**Preparation (1 month before):**
- Select scenario (e.g., earthquake damaging facility)
- Develop exercise script and injects
- Notify participants (no surprise drills for safety reasons)
- Arrange observers and evaluators
- Prepare exercise materials (maps, status boards, props)

**Execution (Exercise Day):**
1. **Kickoff (0900):** Briefing on scenario and safety rules
2. **Inject 1 (0915):** Earthquake reported, building inspection needed
3. **Inject 2 (0945):** Structural damage found, facility evacuation ordered
4. **Inject 3 (1015):** IT systems offline, remote work activation needed
5. **Inject 4 (1100):** Partner manufacturer contacted, production transfer discussed
6. **Inject 5 (1130):** Customer inquiries flooding in, communication needed
7. **Wrap-Up (1300):** Hot wash (immediate feedback), exercise conclusion

**Evaluation Criteria:**
- Emergency Response Team assembly time
- Decision-making speed and quality
- Communication effectiveness
- Technical recovery procedures execution
- Adherence to BCP procedures
- Identification of plan gaps

**Post-Exercise (Within 2 weeks):**
- After-Action Report documenting:
  - What went well
  - What needs improvement
  - Specific action items
  - BCP updates required
- Assign responsibilities for corrections
- Schedule follow-up review in 3 months

### 10.4 Plan Maintenance

**Update Triggers:**
- Annual scheduled review
- After any BCP activation (real incident)
- After testing identifies gaps
- Organizational changes (new systems, personnel, facilities)
- Regulatory changes
- Major supplier or customer changes

**Review Process:**
1. BCP Coordinator reviews entire plan
2. Section owners review their areas (IT, OT, Operations, etc.)
3. Update contact lists (verify accuracy monthly)
4. Verify supplier agreements and contracts still valid
5. Update financial impact estimates
6. Revise based on lessons learned from tests and real incidents
7. Obtain executive approval for major changes
8. Redistribute updated plan to all authorized personnel
9. Brief changes at next team meeting

**Change Management:**
- Version control: Increment version number for changes
- Track changes: Document in version history table
- Sunset old versions: Archive with 5-year retention
- Notification: Email summary of significant changes

---

## 11. Recovery Metrics and Success Criteria

### 11.1 Key Performance Indicators (KPIs)

| KPI | Target | Measurement |
|-----|--------|-------------|
| Emergency Response Team assembly time | <30 minutes | Time from activation to team assembled |
| Time to initial customer communication | <2 hours | Time from incident to first customer notification |
| IT system RTO achievement | <4 hours | Time to restore critical IT systems |
| OT system RTO achievement | <8 hours | Time to resume production |
| Data loss (RPO achievement) | <1 hour | Amount of data lost in backup restoration |
| Production capacity at 24 hours | >60% | Percentage of normal production capacity |
| Full operational restoration | <72 hours | Time to 100% normal operations |
| Employee safety incidents during recovery | 0 | Number of injuries during BCP activation |

### 11.2 Success Criteria

**Immediate Success (0-4 hours):**
- All personnel accounted for and safe
- Emergency Response Team assembled
- Damage assessed and documented
- Initial customer communication sent
- Critical systems isolated or secured

**Short-Term Success (4-24 hours):**
- Critical IT systems restored
- Production resumed (at least 60% capacity)
- Alternative arrangements activated if needed
- Regular updates provided to stakeholders
- Financial impact assessment completed

**Long-Term Success (1-7 days):**
- Full production capacity restored
- All backlogged orders fulfilled
- Normal business operations resumed
- Post-incident review conducted
- BCP updates identified

### 11.3 Financial Metrics

**Cost Tracking During Incident:**
- Emergency procurement
- Overtime labor
- Equipment rental or replacement
- Contractor services
- Expedited shipping
- Partner manufacturing costs
- Lost revenue
- Insurance deductible

**Insurance Claim Documentation:**
- Itemized loss inventory
- Photos and videos of damage
- Repair estimates from contractors
- Revenue impact calculations
- Extra expense receipts
- Business interruption claim justification

---

## 12. Appendices

### Appendix A: Emergency Contact List

**Key Personnel** (updated monthly)

| Name | Role | Mobile | Email | Home Phone | Alternate Contact |
|------|------|--------|-------|------------|-------------------|
| [CEO Name] | Incident Commander | +34 XXX XXX XXX | ceo@zabalagailetak.com | +34 XXX XXX XXX | Spouse: +34 XXX |
| [CISO Name] | BCP Coordinator | +34 XXX XXX XXX | ciso@zabalagailetak.com | +34 XXX XXX XXX | Spouse: +34 XXX |
| [CFO Name] | Finance Lead | +34 XXX XXX XXX | cfo@zabalagailetak.com | +34 XXX XXX XXX | Spouse: +34 XXX |
| [IT Manager] | IT Recovery Lead | +34 XXX XXX XXX | it@zabalagailetak.com | +34 XXX XXX XXX | Spouse: +34 XXX |
| [Ops Manager] | Operations Lead | +34 XXX XXX XXX | ops@zabalagailetak.com | +34 XXX XXX XXX | Spouse: +34 XXX |

**External Emergency Services**

| Service | Contact | Purpose |
|---------|---------|---------|
| Emergency Services | 112 | Fire, Police, Medical |
| Police (National) | 091 | Security incidents |
| Fire Department | 080 | Fire emergencies |
| Civil Guard | 062 | Rural areas, serious crimes |
| INCIBE | +34 017 | Cybersecurity incidents |
| AEPD | +34 901 100 099 | Data breach reporting |

**Critical Suppliers**

| Supplier | Product/Service | Primary Contact | Alternate | Phone |
|----------|----------------|-----------------|-----------|-------|
| [Flour Supplier A] | Wheat flour | [Contact] | [Contact] | +34 XXX |
| [Flour Supplier B] | Wheat flour | [Contact] | [Contact] | +34 XXX |
| [Packaging Co.] | Boxes, wrapping | [Contact] | [Contact] | +34 XXX |
| [Electric Utility] | Power supply | [Account Manager] | Emergency: 900 XXX | +34 XXX |
| [ISP A] | Internet (primary) | [Support] | Emergency: 24/7 | +34 XXX |
| [ISP B] | Internet (backup) | [Support] | Emergency: 24/7 | +34 XXX |
| [AWS/Azure] | Cloud hosting | [Account Manager] | Support Portal | +34 XXX |

**Partner Manufacturers**

| Company | Capacity | Contact | Phone | Agreement Status |
|---------|----------|---------|-------|------------------|
| [Partner A] | 40% our volume | [Contact] | +34 XXX | MOU signed 2025-06 |
| [Partner B] | 30% our volume | [Contact] | +34 XXX | MOU signed 2025-08 |

**Insurance and Legal**

| Service | Company | Contact | Phone | Policy # |
|---------|---------|---------|-------|----------|
| Business Insurance | [Insurer] | [Broker] | +34 XXX | POL-XXXXX |
| Cyber Insurance | [Insurer] | [Broker] | +34 XXX | CYB-XXXXX |
| Legal Counsel | [Law Firm] | [Attorney] | +34 XXX | N/A |

### Appendix B: IT System Inventory

**Critical IT Assets** (see full Asset Register for complete inventory)

| System | Location | Purpose | Backup Frequency | DR Strategy |
|--------|----------|---------|------------------|-------------|
| Web Application | AWS eu-west-1 | Order management | Real-time replication | Multi-region failover |
| Database (MongoDB) | AWS eu-west-1 | Customer/order data | Hourly incremental | Replica set + backups |
| Email Server | Microsoft 365 | Communication | Continuous (cloud) | Cloud resilient |
| File Server | On-premises + OneDrive | Documents | Hourly | Cloud sync |
| Firewall | On-premises | Network security | Config backup daily | Spare hardware on-site |
| SCADA Server | On-premises | Production monitoring | Daily | Hot standby server |

### Appendix C: Manual Operation Procedures

**Manual Operation of Production Line (PLC Failure)**

**Safety Prerequisites:**
- Emergency stop system functional (independent of PLC)
- All personnel trained on manual procedures
- Enhanced supervision (2 personnel minimum)

**Procedure:**
1. Switch control panel to "Manual" mode
2. Manually start mixer (verify speed with tachometer)
3. Load ingredients per recipe (use scales, not automated dispensing)
4. Monitor mixing time with stopwatch (10 minutes for standard recipe)
5. Manually trigger conveyor to oven (use manual controls)
6. Monitor oven temperature manually (verify with IR thermometer every 5 minutes)
7. Manually advance baking cycle (12 minutes at 180°C)
8. Manually activate cooling conveyor
9. Hand-transfer to packaging (automated packaging offline in manual mode)
10. Quality check every batch (mandatory)

**Limitations:**
- Production rate: 30% of normal capacity
- Enhanced quality monitoring required
- Maximum continuous operation: 4 hours (crew fatigue)
- Not suitable for complex recipes (stick to top 3 SKUs)

### Appendix D: Backup Verification Checklist

**Monthly Backup Test** (IT team performs)

- [ ] Select random backup from previous week
- [ ] Verify backup file integrity (checksum matches)
- [ ] Restore to test environment (isolated from production)
- [ ] Verify database connectivity
- [ ] Check data completeness (record counts match production)
- [ ] Spot-check data quality (5 random recent orders)
- [ ] Test application functionality (login, order search, report generation)
- [ ] Measure restoration time (compare to RTO target)
- [ ] Document results in backup log
- [ ] Report any issues to IT Manager immediately

### Appendix E: Incident Log Template

**Business Continuity Incident Log**

| Incident ID: | BCP-20XX-XXX | Date/Time: | [Start time] |
|--------------|--------------|------------|--------------|
| **Incident Commander** | [Name] | **BCP Coordinator** | [Name] |
| **Incident Type** | [ ] Cyber [ ] IT Failure [ ] OT Failure [ ] Facility [ ] Natural Disaster [ ] Other: _____ |
| **Affected Systems** | |
| **Impact Assessment** | [ ] Critical [ ] High [ ] Medium [ ] Low |

**Timeline:**

| Time | Event | Action Taken | By Whom |
|------|-------|--------------|---------|
| | | | |
| | | | |

**Resource Usage:**

| Resource | Cost | Vendor | Notes |
|----------|------|--------|-------|
| | | | |

**Communications Issued:**
- [ ] Internal employee notification (Time: ____)
- [ ] Customer notification (Time: ____)
- [ ] Supplier notification (Time: ____)
- [ ] Regulatory notification (Time: ____)
- [ ] Media statement (Time: ____)

**Recovery Status:**
- [ ] BCP activated (Time: ____)
- [ ] Emergency Response Team assembled (Time: ____)
- [ ] IT systems restored (Time: ____)
- [ ] OT systems restored (Time: ____)
- [ ] Production resumed (Time: ____ at ___% capacity)
- [ ] Full operations restored (Time: ____)
- [ ] BCP deactivated (Time: ____)

**Post-Incident:**
- [ ] Post-incident review scheduled (Date: ____)
- [ ] After-action report completed (Date: ____)
- [ ] BCP updates identified
- [ ] Insurance claim filed (Date: ____ Amount: €_____)

---

**END OF BUSINESS CONTINUITY PLAN**

---

**Document Distribution:**

Printed copies stored in emergency kits at:
1. CEO office (Building A, Room 301)
2. CISO office (Building A, Room 201)
3. Production supervisor office (Building B, Floor 1)
4. Security office (Main entrance)
5. Off-site: [Specify secure location, e.g., CEO's home safe]

Digital copies accessible via:
- ISMS document repository (access-controlled)
- Cloud storage: [URL] (MFA required)
- Emergency Response Team members' company laptops (encrypted)

**IMPORTANT: This document contains sensitive information about Zabala Gailetak's recovery capabilities and vulnerabilities. Handle with appropriate confidentiality controls.**
