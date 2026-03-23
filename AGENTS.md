Project: LMP-Dayta

Purpose

This project is a role-based internal web application for structured daily reporting and organizational health monitoring.
The system is used by Manager, HoD, Director, and Admin with clearly separated access and responsibilities.
The goal of the project is to build the product exactly according to the provided project documents, without adding extra features, extra roles, or extra workflows outside the approved scope.

Primary Objective for Codex

Your task is to implement the application, not redefine the product.
Do not invent product behavior.
Do not expand scope.
Do not simplify role boundaries.
Do not merge pages or permissions unless explicitly specified.
When something is unclear, prefer the most conservative implementation that follows the existing documents and preserves role restrictions.

Required Reference Documents

Always treat the following files as the source of truth:

* STACK_DECISIONS.md
* ROLE_ACCESS_MATRIX.md
* PAGE_SPECS.md
* DATA_SCHEMA.md
* WORKFLOW_RULES.md
* UI_SYSTEM.md
* BUILD_PLAN.md

Priority Order of Truth

When conflicts happen, use this order:

1. AGENTS.md
2. ROLE_ACCESS_MATRIX.md
3. PAGE_SPECS.md
4. WORKFLOW_RULES.md
5. DATA_SCHEMA.md
6. STACK_DECISIONS.md
7. UI_SYSTEM.md
8. BUILD_PLAN.md

General Build Rules

Build only what is explicitly defined in the project documents.
Do not add hidden features, helper modules, or “smart defaults” that change product behavior.
Do not introduce new business logic that is not defined.
Do not create extra menus, extra dashboards, extra summary modes, or extra automation beyond the documents.
Do not rename business concepts unless the documents explicitly require it.
Keep terminology consistent across routes, components, pages, forms, database, and UI labels.

Project Nature

This is not a public consumer app.
This is not a social app.
This is not a task management platform.
This is not a chat platform.
This is not a performance appraisal platform.

This is a role-based internal reporting and monitoring system with:

* structured daily entries
* Big Rock alignment
* division and company health monitoring
* flags/findings
* AI summary generation for allowed scopes
* admin-controlled settings and override flow

Tech Discipline

Use only the stack defined in STACK_DECISIONS.md.
Do not replace the framework, libraries, or architecture.
Do not introduce a separate frontend stack unless explicitly allowed.
Do not add unnecessary dependencies.
Prefer simple, maintainable, conventional implementation over clever or abstract architecture.

Code Quality Rules

Write clean, readable, maintainable code.
Use clear naming.
Prefer straightforward structure over premature abstraction.
Avoid dead code.
Avoid speculative architecture.
Avoid excessive service splitting unless it improves clarity.
Avoid duplicated business rules across multiple layers.
Keep role checks and workflow rules centralized where possible.
Keep UI components reusable where it meaningfully reduces duplication.

Authentication and Authorization

Authentication and authorization are critical.
Never assume access.
Always enforce role-based access at the correct layer.
A page hidden in the UI is not enough; access must also be protected in routing/controllers/components/policies as appropriate to the chosen stack.
Every role must only see and do what is explicitly allowed in ROLE_ACCESS_MATRIX.md.

Core Role Boundaries

Manager:

* works only with own daily entries and own history
* cannot access division-wide or company-wide monitoring
* cannot manage Big Rocks
* cannot manage users, divisions, settings, or overrides

HoD:

* has all personal daily-entry capabilities similar to Manager
* can monitor own division only
* can manage Big Rocks for own division only
* can view team entries in read-only scope when defined
* cannot manage other divisions
* cannot access company-wide director scope
* cannot perform admin functions

Director:

* monitoring role only
* does not submit daily entries
* can view company and division monitoring scopes as defined
* can generate AI summaries only for allowed scopes
* cannot manage Big Rocks
* cannot perform admin functions
* cannot override entries

Admin:

* system management role
* can manage users, divisions, HoD assignment, report settings, and override flows as defined
* is not treated as Director by default
* is not a normal reporting user by default unless explicitly defined elsewhere
* override actions must preserve auditability

Page and Navigation Rules

Follow PAGE_SPECS.md exactly.
Each role should only see the sidebar items defined for that role.
Do not add generic menu items such as Analytics, Settings, Reports, Team, or Overview unless explicitly specified.
Dashboard pages are shortcut and orientation pages, not a place to invent extra workflows.
Do not merge role dashboards into one universal dashboard unless explicitly specified.

Workflow Rules

Follow WORKFLOW_RULES.md exactly.
Plan and realization must follow their defined open/locked/closed rules.
Do not allow user actions outside the allowed workflow windows unless admin override flow explicitly permits it.
Do not skip required reasons, required states, or required validation steps.
Do not replace archive/deactivate behavior with hard delete where history must be preserved.
Where workflow documents require audit trails, always preserve them.

Data and Schema Rules

Use DATA_SCHEMA.md as the schema contract.
You may implement the schema through framework migrations and ORM models, but do not change the business meaning of entities.
Do not remove important entities just because they seem mergeable.
Do not hard delete records that should remain for history unless the documents explicitly allow it.
Preserve relational integrity and history-sensitive data.
Use explicit statuses and enums where appropriate to reflect the business states defined in the documents.

Migration Rules

All schema changes must go through migrations.
Do not make silent schema changes outside migrations.
Keep migration names clear and conventional.
Prefer additive, safe schema evolution over destructive changes.
If a business requirement implies audit history, structure the schema to preserve it.

UI and Interaction Rules

Follow UI_SYSTEM.md exactly.
The product should feel modern, clean, structured, and business-focused.
Do not create flashy consumer-style UI.
Do not use overly dense admin-table styling unless the page truly needs it.
Use clear layout hierarchy, consistent spacing, and reusable cards/forms/tables.
Interactive behavior should improve usability, not create visual noise.
Prefer clarity over animation.

State Handling Rules

Every page and feature must correctly represent applicable states, including where relevant:

* locked
* open
* closed
* draft
* submitted
* active
* archived
* empty
* loading
* unauthorized

Do not ignore edge states.
Do not hide important status from users when the workflow depends on it.
Locked or unavailable actions should be visibly understandable where appropriate.

AI Summary Rules

AI summary is an assistant feature, not the source of truth.
Do not treat AI summary as replacing flags, metrics, or structured data.
Only expose AI summary generation to the roles and scopes explicitly allowed.
Do not add extra AI features such as chat assistant, recommendations, scoring, or auto-decisioning unless explicitly defined.

Flags and Monitoring Rules

Flags/findings are important business outputs.
Do not present them as decorative badges only.
They must be visible in the correct contexts defined by the page specifications.
Do not invent new severity systems or risk models unless defined in the documents.

Admin Override Rules

Override is a controlled correction workflow, not a free edit mode.
Require the defined reason fields.
Preserve auditability.
Do not implement override in a way that destroys original context if the documents require traceability.
Do not expose override actions to non-admin roles.

Big Rock Rules

Big Rocks belong to division planning and alignment.
Only allowed roles may manage them.
Do not assume every daily-entry item must map to a Big Rock.
Support non-Big-Rock work types where defined.
Prefer archive/deactivate over destructive removal when history matters.

Implementation Style

Prefer conventional project structure.
Prefer explicit files over magical abstractions.
Prefer readable component boundaries.
Keep naming aligned with the business language used in the documents.
Use seeders/factories/mock data only when they support development or demo flows and do not distort the actual data model.

What Not to Do

Do not redesign the product.
Do not invent additional roles.
Do not create multi-tenant logic unless specified.
Do not create public-facing marketing pages unless specified.
Do not add notifications, exports, imports, charts, filters, or settings pages beyond what is defined.
Do not infer missing features from “common SaaS patterns.”
Do not replace restricted business rules with more permissive behavior.
Do not silently downgrade strict access rules for convenience.

Decision Rule When Unclear

If implementation details are unclear:

* first follow the explicit page spec
* then follow role restrictions
* then follow workflow restrictions
* then choose the most conservative implementation
* do not invent new product logic to “fill gaps”

Definition of Done

A feature is only considered done when:

* it follows the relevant page spec
* it respects role access rules
* it follows workflow rules
* it uses the approved stack
* it reflects the intended states correctly
* it does not introduce out-of-scope behavior
* it is readable and maintainable

Final Instruction

Implement the system faithfully.
Stay inside the defined scope.
Preserve role boundaries strictly.
Prefer clarity, consistency, and maintainability.
Do not improvise on product decisions.
