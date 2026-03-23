Project Stack Decisions

Core Principle

This project must use a simple, maintainable, Laravel-first architecture.
Do not introduce a split frontend-backend architecture.
Do not build this as a separate SPA.
Do not replace Laravel with another framework.
Do not change the stack unless explicitly instructed.

Primary Stack

Backend Framework
Laravel 13

Language
PHP 8.5+

Database
PostgreSQL

Frontend Rendering Approach
Laravel Blade for layout and page composition
Laravel Livewire for interactive page behavior
Alpine.js for lightweight frontend interactions only where needed

Styling
Tailwind CSS

UI Component Direction
Use a clean custom component system built with Blade, Livewire, Tailwind, and Alpine.
Do not depend on a heavy admin template.
Do not use a theme that forces the product into a generic admin-dashboard look.

Icons
Use a single consistent icon library.
Prefer Lucide or Heroicons.
Do not mix multiple icon sets without need.

Charts
Use a simple chart library that works cleanly with Laravel pages.
Prefer ApexCharts or Chart.js.
Use one chart library only.
Do not introduce multiple chart libraries.

Tables and Forms
Use Blade and Livewire components for tables, filters, forms, cards, and modals.
Keep patterns consistent across all modules.

Authentication
Use Laravel authentication with session-based login.
Do not build token-based auth for this internal system unless explicitly required later.

Authorization
Use Laravel middleware, policies, gates, and role-based checks as appropriate.
Authorization must be enforced at the backend level, not only hidden in the UI.

Role Model

The system must support these roles only:

* Manager
* HoD
* Director
* Admin

Do not add extra roles unless explicitly documented.
Do not merge role behavior.
Do not treat Admin as Director by default.
Do not treat Director as a reporting user by default.

Architecture Direction

Application Style

This project is a server-driven internal web application.
The default rendering model is server-side using Laravel Blade.
Livewire is used to add interactivity to pages that need dynamic filtering, forms, modals, and partial updates.
Alpine is used only for lightweight UI interaction.

Do not build this as:

* API-first unless explicitly needed for a specific integration
* separate React frontend
* separate Vue frontend
* mobile-first app architecture
* microservice architecture

Project Structure Direction

Use conventional Laravel project structure.
Keep domain logic organized and readable.
Prefer clear separation between:

* controllers or page entry points
* Livewire components
* models
* services where useful
* policies / authorization
* form requests / validation
* migrations / seeders

Do not create unnecessary abstraction layers.
Do not over-engineer service classes for trivial operations.
Do not create repository patterns unless clearly needed.

Database Direction

Database Engine
PostgreSQL is required.

Database Ownership
Schema changes must be managed through Laravel migrations.
Do not make schema changes manually outside migration files.
Do not treat the database structure as flexible or inferred.

ORM
Use Eloquent as the primary ORM.

Schema Design Principles
Use explicit tables for core business entities.
Preserve history where the business flow requires traceability.
Prefer archive/deactivate patterns over destructive deletion where history matters.
Use foreign keys where appropriate.
Use timestamps consistently.
Use enums or clear status fields for business states when appropriate.

Data Integrity Rules
Do not allow schema shortcuts that weaken role boundaries, workflow rules, or auditability.
Do not flatten important relationships just for convenience.
Do not merge conceptually different entities into one table unless explicitly defined in DATA_SCHEMA.md.

Frontend Direction

Rendering Strategy
Use Blade layouts as the application shell.
Use Livewire components for:

* dynamic filters
* dashboard cards that react to filters
* plan and realization forms
* history filtering
* Big Rock management
* summary generation sections
* admin CRUD flows
* override flows

Use Alpine only for:

* dropdowns
* simple toggles
* modal visibility when appropriate
* lightweight UI behavior

Do not use Alpine for large business logic.
Do not duplicate business logic in JavaScript.

UI Principles
The interface must feel modern, clean, structured, and business-focused.
Use consistent spacing, card layouts, form patterns, filter layouts, and table styling.
Avoid flashy animation.
Avoid visually noisy dashboards.
Avoid template-style clutter.

Tailwind Usage Rules
Use Tailwind as the primary styling system.
Keep utility usage consistent.
Extract reusable Blade or Livewire components for repeated UI patterns.
Do not mix competing CSS frameworks.

Interactivity Rules

Use Livewire for pages that need a better user experience without leaving Laravel conventions.
Prefer server-driven interactivity over custom JavaScript-heavy solutions.
Do not introduce frontend state libraries.
Do not build a component architecture that behaves like a separate SPA.

Recommended Use of Livewire

Livewire should be the default choice for interactive internal pages such as:

* Manager daily entry
* Manager history filters
* HoD Big Rock management
* HoD division summary filters
* Director company and division filters
* Admin user management
* Admin division management
* Admin override workflows
* report settings forms

Validation Direction

Use Laravel validation as the main validation layer.
Prefer Form Requests or well-structured Livewire validation rules depending on page type.
Validation rules must reflect business rules from WORKFLOW_RULES.md and ROLE_ACCESS_MATRIX.md.
Do not rely only on client-side validation.

Authorization Direction

Use Laravel middleware for route-level protection.
Use policies, gates, or clear service-level checks for action-level authorization.
Every sensitive action must be protected.
UI hiding is not sufficient protection.

State Management Direction

Business states must be represented explicitly in backend logic and reflected in UI.
Examples include:

* locked
* open
* closed
* draft
* submitted
* active
* archived
* unauthorized

Do not fake states only in the frontend.
Do not allow UI state to contradict business state.

AI Summary Integration Direction

AI summary should be implemented as a controlled feature for allowed scopes only.
It must not replace structured data, flags, or dashboard metrics.
If the actual AI integration is not yet ready, implement the feature in a way that allows later connection without redesigning the whole page.
Do not add extra AI assistant features beyond the defined summary use case.

Notification Direction

If Discord or other notifications are implemented, they must follow business rules and system triggers only.
Do not create manual notification behavior unless explicitly defined.
Do not build a user chat or messaging system.

File and Dependency Rules

Dependency Rules
Add only dependencies that directly support the approved stack and defined requirements.
Avoid unnecessary packages.
Avoid overlapping packages that solve the same problem.
Do not add packages that impose a new architecture direction.

Preferred Dependency Categories

* Laravel core ecosystem
* Livewire ecosystem when justified
* Tailwind ecosystem
* one icon set
* one chart library
* PostgreSQL-compatible packages when needed
* file import/export support only if later approved in scope

Do Not Introduce Without Explicit Need

* React
* Vue as separate frontend app
* Inertia unless explicitly approved later
* Redux-like frontend state tools
* websocket architecture by default
* queue-heavy event architecture unless clearly required
* multiple chart libraries
* multiple UI kits
* heavy admin templates

Testing Direction

Minimum Testing Direction
Write tests for critical role access, core workflow restrictions, and important business rules.
Prefer Laravel feature tests for end-to-end business behavior.
Add unit tests where service logic becomes meaningful.
Do not over-focus on low-value test coverage while core workflow behavior remains untested.

Priority Test Areas

* authentication access
* role restrictions
* daily entry workflow restrictions
* Big Rock access boundaries
* Director monitoring access
* admin override permissions
* report setting effects on availability

Seeder and Demo Data Direction

Use seeders and factories to support local development and demonstration.
Seed data should reflect the actual business model and allowed roles.
Do not create demo data that contradicts the role model or workflow rules.

Performance Direction

Build for clarity first, then optimize obvious hotspots.
Do not optimize prematurely.
Use eager loading and reasonable query structure where needed.
Keep dashboard queries understandable and maintainable.
If a page becomes data-heavy, improve query efficiency without changing product behavior.

Deployment Assumption

Assume a standard Laravel deployment environment with PostgreSQL.
Do not make infrastructure assumptions that require a highly specialized platform.
Do not hardcode local-only behavior.

Coding Conventions

Use Laravel conventions wherever possible.
Prefer descriptive names.
Prefer maintainable Blade and Livewire component naming.
Keep business terminology aligned with project documents.
Do not rename core concepts such as Big Rock, Daily Entry, Plan, Realization, Override, or Flag without explicit instruction.

Non-Negotiable Stack Rules

Use Laravel 13.
Use PostgreSQL.
Use Blade for page structure.
Use Livewire for interactivity.
Use Alpine only for lightweight UI behavior.
Use Tailwind for styling.
Use Eloquent and Laravel migrations for data access and schema management.
Keep the system server-driven and Laravel-first.

Do Not Do These

Do not convert the project into a separate frontend app.
Do not introduce React or Vue as the main UI layer.
Do not replace Blade with a frontend framework architecture.
Do not create an API-first design unless explicitly required by a future document.
Do not use MySQL or SQLite as the primary intended production database.
Do not add a heavy admin template.
Do not improvise new stack decisions during implementation.

Final Decision

This project must be built with:

* Laravel 13
* PHP 8.5+
* PostgreSQL
* Blade
* Livewire
* Alpine.js
* Tailwind CSS
* Eloquent ORM
* Laravel migrations
* a single consistent icon library
* a single chart library

This stack is fixed unless explicitly changed by project instruction.
