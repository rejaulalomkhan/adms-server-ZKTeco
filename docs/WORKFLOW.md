## Workforce Attendance & Payroll System — End-to-End Workflow

This document defines how the system will support attendance management, shift and roster scheduling (including split shifts and rotating rosters), manual overrides, overtime calculations, holiday registry, dashboard summaries, device (e.g., ZKTeco) integration by area/office, access control, and reporting.

### 1) Core Concepts
- **Employee**: A user who can be scheduled for shifts and whose attendance is recorded. Existing `users` table will be leveraged; we may extend via an `employees` profile if needed (e.g., employment status, grade, cost center).
- **Shift Template**: A reusable shift with defined start/end times, overnight flag, break minutes, grace time, and late/early policies.
- **Roster (Rotation Rule)**: Defines a repeating weekly (or N-week) cycle that maps each week index (or day) to a `Shift Template`. Used for automatic scheduling like week1: 09:00–21:00, week2: 21:00–09:00.
- **Manual Assignment**: Explicit per-employee assignment for a specific date or date range that overrides rotation rules.
- **Attendance Event**: Raw punches (in/out) from devices (e.g., ZKTeco) or manual entries, normalized into `attendances` records per employee per day/shift.
- **Overtime**: Time beyond scheduled shift rules measured per policy (pre-shift, post-shift, weekly, holiday overtime). May be calculated on-demand or persisted for audits.
- **Holiday**: Registered non-working days (public holidays, company holidays, weekends) with optional area/office scope.
- **Area & Office**: Organizational hierarchy for deploying devices and scoping employees/reports (e.g., Country → City → Office/Floor).
- **Access Control**: Roles/permissions to guard scheduling, device admin, approvals, and reporting.

### 2) Scheduling & Attendance Flow
1. Define `Shift Templates` (e.g., Day 09:00–21:00, Night 21:00–09:00, Split shifts).
2. Create `Roster Rotation Rules` for employees or groups:
   - N-week cycles: For example, week 1 → Day shift, week 2 → Night shift, repeat.
   - Day-level mapping when needed (e.g., Mon-Fri one shift, weekend another).
3. Apply `Manual Assignments` for exceptions, backfills, or ad-hoc changes.
4. Devices send raw logs (punches) → normalized into `attendances` per employee-day with linked shift context.
5. Late, early-leave, absence, and overtime are computed using shift rules and holidays.
6. Approvals (optional): Supervisors can confirm/adjust overtime or attendance anomalies.
7. Payroll exports aggregate approved attendance and overtime by period.

### 3) Shifts & Rotation Details
- Shift Template fields:
  - name, code, start_time, end_time, is_overnight, break_minutes
  - grace minutes (late tolerance), round rules (ceil/floor to X minutes)
  - expected_hours (derived or explicit)
- Rotation (Roster) options:
  - cycle_length_weeks: number of weeks in rotation
  - mapping (week_index → shift_id), optionally day_of_week mapping
  - effective_date, expiry_date
- Manual Assignment precedence:
  - Highest priority when present for a date or range
  - Otherwise apply rotation rules
  - Otherwise fallback default shift (optional)

### 4) Overtime Policy
- Types: pre-shift, post-shift, off-day/holiday, weekly cumulative.
- Rules:
  - thresholds (e.g., minimum 30 min), rounding (e.g., 15-min block), approval required flags
  - max daily/weekly caps
- Calculation timing:
  - On-demand for dashboards/reports
  - Background job to persist `overtime_entries` for audit/exports

### 5) Holidays
- Global holidays and scoped holidays (by area/office).
- Impacts lateness/absence and overtime classification.

### 6) Dashboard Summary (default Today)
- KPIs:
  - total employees
  - present today
  - late today
  - absent today
- Filters: Yesterday, This Week, This Month, Custom Range
- Performance: Pre-aggregations or cached queries; auto-refresh.

### 7) Reporting
- Attendance Sheet: per employee per day with in/out, shift, late, early, absence, overtime.
- Lateness Report: counts, durations, top offenders.
- Absence Report: total absences by period/office/area.
- Overtime Report: detailed and summarized (daily/weekly), approval status.
- Export: CSV/XLSX, date range and scope filters.

### 8) Devices & Integration (ZKTeco and others)
- Device registry with area/office assignment and connection params (IP/port/ADMS keys).
- Pull methods:
  - ADMS/WebHook, SDK polling, file imports.
- Normalization:
  - Map device user IDs to system employees
  - Deduplicate and order events; detect overnight spans
- Error handling & retry queues; device health monitoring.

### 9) Access Control
- Roles: Admin, HR/Payroll, Supervisor, Operator, Viewer.
- Permissions: manage employees, manage shifts/rosters, approve overtime, manage devices, view reports.
- Route middleware and policy classes enforce access per module/action.

### 10) Data Model Additions (Planned)
- shifts
  - id, name, code, start_time, end_time, is_overnight, break_minutes, grace_minutes, expected_hours, active
- shift_rotations
  - id, employee_id (nullable for group default), cycle_length_weeks, effective_date, expiry_date
- shift_rotation_weeks
  - id, rotation_id, week_index, shift_id
- shift_rotation_days (optional granular day mapping)
  - id, rotation_id, week_index, day_of_week, shift_id
- shift_assignments (manual override)
  - id, employee_id, shift_id, start_date, end_date, reason, priority
- holidays
  - id, name, date, is_recurring, area_id (nullable), office_id (nullable)
- overtime_rules (optional per office/area or global)
  - id, name, scope, thresholds, rounding, caps
- overtime_entries (optional persisted results)
  - id, employee_id, date, minutes, type, approved_by, approved_at
- areas, offices
  - areas: id, name, parent_id (hierarchy)
  - offices: id, area_id, name, code
- device_area_office linking
  - devices add office_id (and derive area via office)

Note: We will link to existing `users` as employees to avoid duplication. If extended attributes are required, an `employee_profiles` table may be introduced later.

### 11) APIs & UI (High-Level)
- Employees: CRUD, import, assign office
- Shifts: CRUD
- Rotations: CRUD; set employee rotation; preview calendar
- Assignments: CRUD; override periods
- Holidays: CRUD; office/area scope
- Overtime: calculate, list, approve
- Dashboard: KPI summary with date filter dropdown (today default)
- Reports: attendance, late, absence, overtime; export CSV/XLSX
- Devices: CRUD, test connection, sync logs, assign office

### 12) Implementation Phases
1. Data model & migrations for shifts, rotations, assignments, holidays, overtime (skeleton)
2. Services for schedule resolution (rotation → assignment → final shift)
3. Attendance normalization to attach shift context; compute late/absence
4. Overtime calculation service and approvals
5. Dashboard summary endpoints & UI
6. Holiday register UI and APIs
7. Reporting endpoints and exports
8. Device registry by office; integrate ZKTeco ingestion
9. Access control (roles/permissions)
10. Documentation and tests

### 13) Non-Functional
- Audit logs for changes to schedules/assignments
- Background jobs for heavy calculations/imports
- Idempotent device ingestion
- Timezone-safe date math; overnight shifts across day boundaries
- Performance: indexes on employee_id/date fields; partition strategies if needed

This workflow will guide the step-by-step implementation in the repository.


