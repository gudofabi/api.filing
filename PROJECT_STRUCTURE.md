# Project Structure (Laravel REST API) — Leave Filing API

This document describes the recommended folder structure and responsibilities for implementing the Leave Filing API in Laravel, following the required patterns:
- REST endpoint
- Form Request validation
- Service layer
- Clean controller
- Overlapping date validation
- Holiday-aware deduction
- Database migrations
- PHPUnit test coverage (at least 3 cases)

---

## 1) Folder Layout

```txt
app/
  DTO/
    Leave/
      LeaveFilingData.php

  Exceptions/
    DomainException.php
    LeaveOverlapException.php
    LeavePolicyException.php

  Http/
    Controllers/
      Api/
        LeaveRequestController.php
    Requests/
      StoreLeaveRequest.php

  Models/
    Employee.php
    Holiday.php
    LeaveRequest.php

  Services/
    Leave/
      HolidayCalendar.php
      LeaveFilingService.php
      LeaveOverlapChecker.php
      LeavePolicyService.php

database/
  factories/
    EmployeeFactory.php
    HolidayFactory.php
    LeaveRequestFactory.php

  migrations/
    2026_.._.._create_employees_table.php
    2026_.._.._create_holidays_table.php
    2026_.._.._create_leave_requests_table.php

  seeders/
    HolidaySeeder.php  (optional)

routes/
  api.php

tests/
  Feature/
    LeaveFilingTest.php
  Unit/
    Services/
      Leave/
        HolidayCalendarTest.php      (optional)
        LeaveOverlapCheckerTest.php  (optional)

docs/
  API.md
  PROJECT_STRUCTURE.md