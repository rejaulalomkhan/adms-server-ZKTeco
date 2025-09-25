# ADMS (Attendance Device Management System)

ADMS is a comprehensive Attendance Device Management System designed to handle biometric and access control data from various devices. This system is built using Laravel, a PHP framework, provides functionalities to store, manage user and fingerprint data.

## Features

- Device push integration (ZKTeco ADMS/push protocol)
- Attendance ingest, logs, and status monitoring
- Shift templates, weekly rotations, and manual shift assignments
- Holidays registry, overtime calculation and approvals
- Dashboard (calendar/range filter, recent attendance auto-refresh)
- Reporting (attendance, lateness, absence) with CSV/Excel/PDF export
- Organization modeling (Areas, Offices, Users → Office)

## Screenshots
Device Connected
![App Screenshot](https://github.com/rejaulalomkhan/adms-server-ZKTeco/blob/main/Screenshot_7.png)
Attendance Recorded
![App Screenshot](https://github.com/rejaulalomkhan/adms-server-ZKTeco/blob/main/Screenshot_8.png)
Device Log
![App Screenshot](https://github.com/rejaulalomkhan/adms-server-ZKTeco/blob/main/Screenshot_9.png)
Attendence Log
![App Screenshot](https://github.com/rejaulalomkhan/adms-server-ZKTeco/blob/main/Screenshot_10.png)

## Installation

### Prerequisites

Before you begin, ensure you have the following installed on your system:

- PHP >= 8.0
- Composer
- MySQL or any other supported database
- Web server (Apache, Nginx, etc.)

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/rejaulalomkhan/adms-server-ZKTeco.git adms-server
   cd adms-server
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Copy the `.env` file**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Configure the `.env` file**
   Open the `.env` file and set your database credentials and other environment variables:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=adms
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Run the migrations**
   ```bash
   php artisan migrate
   ```

7. **Serve the application**
   ```bash
   php artisan serve
   ```

8. **Seed initial data (optional but recommended)**
   ```bash
   php artisan db:seed
   ```

### Monitoring Device Status

You can monitor the status of devices by querying the `devices` table where the `online` field indicates the last time the device was online.

---

## End-to-End Guide

### 1) Configure ZKTeco Device (ADMS / Push)
- Ensure the app is reachable from the device (LAN IP or public domain). Open/forward HTTP/HTTPS port.
- On the device, set:
  - Server IP / Domain: your server (e.g., 192.168.1.10 or example.com)
  - Port: 80 (or 443 if HTTPS)
  - Path/URI (if available): `/iclock/cdata`
  - Mode: Realtime/Push enabled

Device will call these endpoints:
```
GET  /iclock/cdata   → handshake and options
POST /iclock/cdata   → push logs (table=ATTLOG)
```

Quick tests:
```bash
curl "http://YOUR_SERVER/iclock/cdata?SN=TESTSN&option=all"
curl -X POST "http://YOUR_SERVER/iclock/cdata?SN=TESTSN&table=ATTLOG&Stamp=1" \
     -H "Content-Type: text/plain" \
     --data-binary $'1001\t2025-09-25 09:05:00\t1\n1001\t2025-09-25 21:10:00\t1'
```

Where it goes:
- `devices`: device serial (`no_sn`) and last online time
- `attendances`: parsed punches (employee_id, timestamp, flags)
- Device/raw logs: `device_log`, `finger_log` (for troubleshooting)

UI navigation:
- Sidebar → Devices, Attendance, Device Log, Finger Log

### 2) Organization (Areas & Offices)
- Create Areas and Offices: Sidebar → Organization → Areas / Offices
- Assign Users to Offices: Sidebar → Organization → User Offices
- Devices can be linked to an Office (DB column `devices.office_id` exists; UI form coming soon)

### 3) Shifts, Rotations, Assignments
- Shifts: define day/night/split shifts with start/end, break, grace
- Rotations: set week-to-shift cycles (e.g., Week1: 09:00–21:00, Week2: 21:00–09:00)
- Manual Assignments: override rotation for a date/range, per employee

Pages: Sidebar → Scheduling → Shifts / Shift Rotations / Shift Assignments

### 4) Dashboard
- Sidebar → Dashboard
- Use the date picker or range dropdown (Today, Yesterday, This Week, This Month)
- KPIs: Total Employees, Present, Late, Absent
- Recent Attendance panel auto-refreshes every 15s (limit selector)

### 5) Holidays
- Sidebar → Holidays: add global or scoped (by Area/Office) holidays
- Holidays influence absence and overtime classification

### 6) Overtime
- Sidebar → Overtime
- Calculate for a date range (pre-shift, post-shift, holiday)
- Rounding/thresholds from global rule; approve entries inline

### 7) Reporting
- Sidebar → Reports → tabs for Attendance, Lateness, Absence
- Server-side tables with export buttons (CSV/Excel/PDF/Print)

---

## Troubleshooting
- Device cannot connect:
  - Confirm server is reachable on the configured port
  - Try HTTP first if device TLS is outdated
  - Ensure the path is `/iclock/cdata`
- No attendance appears:
  - Check Sidebar → Finger Log / Device Log for pushes
  - Verify device time/timezone is correct
  - Confirm `employee_id` mapping matches your users
- Overtime seems off:
  - Ensure shifts/assignments are set for the employee/date
  - Re-run calculation for the range and check approvals

## Roadmap
- Device edit UI (assign device to Office), office-scoped filters across Dashboard/Reports
- Roles & permissions (access control)
- More device protocols and SDK integrations

Refer to `docs/WORKFLOW.md` for the full system workflow and data model.

## Postman Collection

For testing and interacting with the API endpoints, you can use the provided Postman collection:
[Postman Collection](https://github.com/rejaulalomkhan/adms-server-ZKTeco/blob/main/ADMS server ZKTeco.postman_collection.json)


## Authors

- [@rejaulalomkhan](https://github.com/rejaulalomkhan)

## For Improvement and project

contact us saiful.coder@gmail.com

## Contributing

This project helps you and you want to help keep it going? Buy me a coffee:
<br> <a href="https://www.buymeacoffee.com/rejaulalomkhan" target="_blank"><img src="https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png" alt="Buy Me A Coffee" style="height: 61px !important;width: 174px !important;box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;" ></a><br>
or via <br>
<a href="https://saweria.co/rejaulalomkhan">https://saweria.co/rejaulalomkhan</a>

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.