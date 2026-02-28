# AGENT.md — Coding Standards & Working Agreement (Laravel REST API)

This guide defines the coding standards, architecture rules, and acceptance checks for this repository.  
Follow this exactly when implementing features (especially the Leave Filing API exercise).

---

## 1) Principles

- **Clarity over cleverness**: readable, boring code wins.
- **Thin controllers**: controllers only orchestrate request → service → response.
- **Business rules live in services** (not controllers, not requests, not models).
- **Test-first mindset** for anything involving rules (overlap, holidays, edge cases).
- **Small, composable classes**: avoid “god services”.

---

## 2) Layering Rules (Hard Rules)

### Controllers
✅ Allowed:
- Accept Form Request
- Call a service method
- Return response (resource/JSON)

❌ Not allowed:
- Complex conditionals for business rules
- Querying Eloquent directly for business decisions
- Loops calculating deductible days
- Locking logic inside controller

---

### Form Requests
✅ Allowed:
- Input validation (types, required, exists, date rules)
- Basic normalization (e.g., trim strings)

❌ Not allowed:
- Overlap detection queries
- Holiday deduction calculations
- Cross-record domain logic (belongs in service layer)

---

### Services (`app/Services/**`)
✅ Must:
- Own business rules and orchestration
- Be unit-testable
- Use DB transactions where needed
- Throw domain exceptions for domain failures

✅ Recommended:
- Break down logic into helper services (`LeaveOverlapChecker`, `HolidayCalendar`, `LeavePolicyService`)

---

### Models
✅ Allowed:
- Relationships
- Attribute casting
- Basic scopes (simple filtering)

❌ Not allowed:
- Complex business logic that mixes policies and workflows
- “Magical” side-effects on save/update that hide behavior

---

## 3) Naming & Structure

### Files / Classes
- Controllers: `*Controller.php`
- Requests: `Store*Request.php`, `Update*Request.php`
- Services: `VerbNounService.php` or domain-specific names:
  - `LeaveFilingService`
  - `LeaveOverlapChecker`
  - `HolidayCalendar`
  - `LeavePolicyService`

### Methods
- Use **verb-first** names:
  - `fileLeave(...)`
  - `checkOverlap(...)`
  - `calculateDeductibleDays(...)`

### Folders
- Keep domain-specific classes under a domain folder:
  - `app/Services/Leave/*`

---

## 4) Formatting & Style

- PHP: follow **PSR-12**
- Use strict typing when practical:
  - return types on public methods
  - typed parameters where possible
- Prefer early returns over deep nesting.
- Keep methods small:
  - target **< 30 lines** (soft rule)
- Avoid static state.

---

## 5) API Conventions

- Use JSON responses with consistent shape:

Success:
```json
{ "data": { ... } }