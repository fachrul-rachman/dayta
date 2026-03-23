Role Access Matrix

Core Principle

Access must be strict, role-based, and conservative.
Every role must only see and perform what is explicitly allowed.
Do not infer access from UI visibility alone.
If a role is not explicitly allowed to access a page, section, action, or data scope, then access must be denied.

Roles Covered

* Manager
* HoD
* Director
* Admin

No other roles are allowed unless explicitly added in future project documents.

Global Access Rules

All roles:

* can log in using their own credentials
* can access only the parts of the system relevant to their role
* can log out
* can access their own basic account context as needed for session use

No role may:

* access another role’s restricted area unless explicitly allowed
* bypass workflow timing rules unless explicitly allowed
* perform admin override unless they are Admin
* access company-wide monitoring unless explicitly allowed
* manage users, divisions, settings, or assignments unless explicitly allowed
* access hidden routes just because the UI link is not shown

Scope Definitions

Own Scope
Data belonging to the logged-in user only.

Own Division Scope
Data belonging only to the division assigned to the logged-in user.

Company Scope
Aggregated and cross-division data across the organization.

Read Only
User can view data but cannot create, edit, delete, archive, override, or submit changes.

Manage
User can create, edit, update status, archive, or otherwise control the relevant data based on page and workflow rules.

Override
Admin-only correction flow that requires reason and auditability.

Manager Access

Role Summary

Manager is an individual reporting user.
Manager works only with personal reporting and personal history.
Manager does not monitor division-wide or company-wide health in management scope.

Manager Allowed Pages

Manager can access:

* Manager Dashboard
* Daily Entry
* History
* authentication pages required for login/logout

Manager Forbidden Pages

Manager cannot access:

* HoD dashboard areas
* Big Rock Management
* Division Summary
* Division-wide reporting views
* Director Dashboard
* Company monitoring pages
* Division monitoring pages outside personal context
* Admin Home
* User Management
* Division Management
* HoD Assignment
* Report Settings
* Override pages

Manager Data Access

Manager can view:

* own daily entries
* own plan entries
* own realization entries
* own submission states
* own history
* own personal flags where the page spec shows them
* Big Rock options available for own division when filling applicable daily-entry items

Manager cannot view:

* other users’ daily entries
* division-wide aggregated data
* company-wide aggregated data
* admin audit data
* user lists
* division setup lists
* settings management data
* HoD assignment management data
* override logs unless explicitly exposed in future

Manager Allowed Actions

Manager can:

* view own dashboard
* open own daily entry page
* create or update own plan during allowed workflow state
* create or update own realization during allowed workflow state
* save draft for own entry if defined in workflow
* submit own entry when allowed
* view own history
* filter own history by allowed date inputs
* select Big Rock for own entry when relevant and available
* leave Big Rock unselected when work item is not related to Big Rock

Manager Forbidden Actions

Manager cannot:

* manage Big Rocks
* generate division or company summary
* access team entries
* edit another user’s entries
* override entries
* manage users
* manage divisions
* manage report settings
* assign HoD
* archive or deactivate system master data
* access company or division monitoring outside own personal reporting context

HoD Access

Role Summary

HoD is both a reporting user and a division monitoring user.
HoD inherits personal reporting behavior similar to Manager, but also gains access to own division monitoring and own division Big Rock management.

HoD Allowed Pages

HoD can access:

* HoD Dashboard
* Daily Entry
* History
* Big Rock Management
* Division Entries or Review Team Reports
* Division Summary
* authentication pages required for login/logout

HoD Forbidden Pages

HoD cannot access:

* company-wide Director pages
* Director Dashboard
* company-level monitoring pages
* other divisions’ Big Rock management
* other divisions’ team entries
* Admin Home
* User Management
* Division Management
* HoD Assignment
* Report Settings
* Override pages

HoD Data Access

HoD can view:

* own daily entries
* own history
* own division team submission overview
* own division team entries in read-only scope where defined
* own division flags
* own division summary inputs and outputs
* own division Big Rocks
* own division aggregated metrics and charts
* own division AI summary results where allowed

HoD cannot view:

* other divisions’ internal data
* company-wide unrestricted monitoring data
* admin management data
* global user administration data
* report settings administration
* override administration data

HoD Allowed Actions

HoD can:

* do everything Manager can do for own personal reporting scope
* view own division dashboard
* view own division reporting overview
* manage Big Rocks for own division
* create Big Rocks for own division
* edit Big Rocks for own division
* archive or deactivate Big Rocks for own division according to workflow rules
* view team entries for own division in read-only scope where specified
* generate AI summary for own division if the page spec defines this action
* use division filters that stay within own division scope

HoD Forbidden Actions

HoD cannot:

* manage Big Rocks for another division
* edit another user’s daily entries
* override any entry
* access company-wide director monitoring scope
* manage users
* manage divisions
* assign HoD
* manage report settings
* perform admin-only actions
* see cross-division comparison beyond what may be explicitly exposed in future documents

Director Access

Role Summary

Director is a monitoring and decision-support role.
Director does not act as a normal reporting user.
Director monitors company-wide and division-level health based on allowed views.

Director Allowed Pages

Director can access:

* Director Dashboard
* Company page
* Division page
* authentication pages required for login/logout

Director Forbidden Pages

Director cannot access:

* Daily Entry as a reporting workflow
* Manager personal reporting pages
* HoD personal reporting pages
* Big Rock Management
* User Management
* Division Management
* HoD Assignment
* Report Settings
* Override pages

Director Data Access

Director can view:

* company-wide aggregated metrics
* company-wide charts
* company-wide flags and findings in allowed views
* division-level aggregated metrics
* division-level charts
* division-level summaries
* division-level flags
* Big Rock information in read-only monitoring scope where relevant to dashboards and summaries
* AI summary results for allowed scopes

Director cannot view:

* admin-only control data
* raw admin management workflows
* user management controls
* override control interfaces
* editable Big Rock management views
* unrestricted raw entry editing interfaces
* reporting submission forms as an active reporting user

Director Allowed Actions

Director can:

* view director dashboard
* view company monitoring page
* view division monitoring page
* switch monitoring scope among allowed views
* filter by division where applicable
* filter by date range where applicable
* generate AI summary for company scope if defined
* generate AI summary for division scope if defined
* open division detail in read-only monitoring scope

Director Forbidden Actions

Director cannot:

* submit daily entries
* edit daily entries
* manage Big Rocks
* create, edit, archive, or deactivate Big Rocks
* override entries
* manage users
* manage divisions
* assign HoD
* manage report settings
* perform admin-only correction or control workflows

Admin Access

Role Summary

Admin is the system management role.
Admin manages configuration, master data, assignment, and controlled correction workflows.
Admin is not automatically a Director and is not automatically a normal reporting user.

Admin Allowed Pages

Admin can access:

* Admin Home
* User Management
* Division Management
* HoD Assignment
* Report Settings
* Override
* authentication pages required for login/logout

Admin Forbidden Pages

Admin cannot access by default:

* Manager personal reporting pages as a normal reporting workflow
* HoD personal reporting pages as a normal reporting workflow
* Director monitoring pages unless a future document explicitly grants them
* company-level monitoring pages as Director
* division-level monitoring pages as Director
* Big Rock Management as HoD
* personal daily-entry workflow as Manager or HoD unless explicitly defined in future

Admin Data Access

Admin can view:

* user records
* division records
* HoD assignment records
* report setting records
* override target records needed for correction workflows
* audit-relevant metadata needed to support management and traceability

Admin cannot be assumed to view:

* director-only dashboards
* unrestricted monitoring views outside admin purpose
* personal role-based dashboards as if Admin were another role by default

Admin Allowed Actions

Admin can:

* access admin home
* create users
* edit users
* deactivate users according to business rules
* assign role according to allowed role model
* assign division where relevant
* create divisions
* edit divisions
* deactivate divisions according to business rules
* manage HoD assignment
* set active HoD according to assignment rules
* manage report settings
* control plan and realization availability through settings
* enter override workflow
* search and filter override targets using allowed criteria
* perform override actions with required reason
* preserve auditability of override actions

Admin Forbidden Actions

Admin cannot:

* act as Director by default
* generate company or division summaries unless this is explicitly defined later
* submit daily entry as a normal reporting user by default
* manage Big Rocks as HoD by default
* bypass auditability requirements for override
* remove required reasons from override flow
* hard delete history-sensitive records where business rules require preservation

Access Matrix by Feature Area

Authentication

Manager:

* login: allowed
* logout: allowed

HoD:

* login: allowed
* logout: allowed

Director:

* login: allowed
* logout: allowed

Admin:

* login: allowed
* logout: allowed

Dashboard Access

Manager:

* Manager Dashboard: allowed
* HoD Dashboard: forbidden
* Director Dashboard: forbidden
* Admin Home: forbidden

HoD:

* Manager Dashboard: forbidden as a separate role dashboard
* HoD Dashboard: allowed
* Director Dashboard: forbidden
* Admin Home: forbidden

Director:

* Manager Dashboard: forbidden
* HoD Dashboard: forbidden
* Director Dashboard: allowed
* Admin Home: forbidden

Admin:

* Manager Dashboard: forbidden by default
* HoD Dashboard: forbidden by default
* Director Dashboard: forbidden by default
* Admin Home: allowed

Daily Entry Access

Manager:

* own daily entry page: allowed
* own plan input: allowed within workflow rules
* own realization input: allowed within workflow rules
* own draft save: allowed if defined
* own submit: allowed within workflow rules
* others’ daily entry: forbidden

HoD:

* own daily entry page: allowed
* own plan input: allowed within workflow rules
* own realization input: allowed within workflow rules
* own draft save: allowed if defined
* own submit: allowed within workflow rules
* team entries read-only for own division: allowed where specified
* edit team entries: forbidden

Director:

* daily entry page as reporting workflow: forbidden
* edit or submit daily entry: forbidden
* read monitoring outputs derived from entries: allowed

Admin:

* daily entry page as reporting workflow: forbidden by default
* override target access for correction workflow: allowed
* free-form normal entry editing outside override flow: forbidden

History Access

Manager:

* own history: allowed
* others’ history: forbidden

HoD:

* own history: allowed
* team entry review in own division read-only: allowed where specified
* other divisions’ history: forbidden

Director:

* personal reporting history pages: forbidden
* aggregated monitoring history/trends: allowed where defined

Admin:

* personal reporting history pages as normal user flow: forbidden by default
* historical entry access for override/admin context: allowed where needed

Big Rock Access

Manager:

* select available Big Rock in own entry when applicable: allowed
* create Big Rock: forbidden
* edit Big Rock: forbidden
* archive Big Rock: forbidden
* manage Big Rock pages: forbidden

HoD:

* view own division Big Rocks: allowed
* create own division Big Rocks: allowed
* edit own division Big Rocks: allowed
* archive/deactivate own division Big Rocks: allowed according to workflow rules
* manage another division’s Big Rocks: forbidden

Director:

* read-only Big Rock visibility in monitoring context: allowed where relevant
* create/edit/archive Big Rock: forbidden
* access Big Rock management page: forbidden

Admin:

* Big Rock management as HoD workflow: forbidden by default unless explicitly added later

Summary Access

Manager:

* personal AI summary: forbidden unless explicitly added later
* division summary: forbidden
* company summary: forbidden

HoD:

* division summary page for own division: allowed
* generate AI summary for own division: allowed where specified
* company summary: forbidden
* other division summary: forbidden

Director:

* company summary: allowed where specified
* division summary: allowed where specified
* generate AI summary for allowed monitoring scopes: allowed

Admin:

* summary generation: forbidden by default unless explicitly defined later

Flags and Findings Access

Manager:

* personal flags in own context: allowed where page spec includes them
* division flags: forbidden
* company flags: forbidden

HoD:

* own division flags: allowed
* other division flags: forbidden
* company-wide flags: forbidden unless specifically exposed in future

Director:

* company flags: allowed where defined
* division flags: allowed where defined

Admin:

* flags only where needed for admin control context, not as director monitoring by default

User Management Access

Manager:

* forbidden

HoD:

* forbidden

Director:

* forbidden

Admin:

* allowed

Division Management Access

Manager:

* forbidden

HoD:

* forbidden

Director:

* forbidden

Admin:

* allowed

HoD Assignment Access

Manager:

* forbidden

HoD:

* forbidden

Director:

* forbidden

Admin:

* allowed

Report Settings Access

Manager:

* forbidden

HoD:

* forbidden

Director:

* forbidden

Admin:

* allowed

Override Access

Manager:

* forbidden

HoD:

* forbidden

Director:

* forbidden

Admin:

* allowed with reason and auditability requirements

Read / Create / Update / Archive / Override Matrix

Users

Manager:

* read: own basic user context only
* create: no
* update: no administrative update
* deactivate: no
* override: no

HoD:

* read: own basic user context only
* create: no
* update: no administrative update
* deactivate: no
* override: no

Director:

* read: own basic user context only
* create: no
* update: no administrative update
* deactivate: no
* override: no

Admin:

* read: yes
* create: yes
* update: yes
* deactivate: yes
* override: not applicable as user override, use admin management flow only

Divisions

Manager:

* read: no administrative division management access
* create: no
* update: no
* deactivate: no
* override: no

HoD:

* read: own division context only for monitoring and Big Rock ownership
* create: no
* update: no division master-data update
* deactivate: no
* override: no

Director:

* read: yes in monitoring context
* create: no
* update: no
* deactivate: no
* override: no

Admin:

* read: yes
* create: yes
* update: yes
* deactivate: yes
* override: not applicable

Big Rocks

Manager:

* read: selectable options only in own entry context
* create: no
* update: no
* archive: no
* override: no

HoD:

* read: yes for own division
* create: yes for own division
* update: yes for own division
* archive: yes for own division
* override: no

Director:

* read: yes, read-only where relevant
* create: no
* update: no
* archive: no
* override: no

Admin:

* read: not as default management area
* create: no by default
* update: no by default
* archive: no by default
* override: no by default

Daily Entries

Manager:

* read: own only
* create: own only within workflow
* update: own only within workflow
* submit: own only within workflow
* archive: no
* override: no

HoD:

* read: own plus team read-only within own division where specified
* create: own only within workflow
* update: own only within workflow
* submit: own only within workflow
* archive: no
* override: no

Director:

* read: no raw reporting workflow access, only monitoring outputs
* create: no
* update: no
* submit: no
* archive: no
* override: no

Admin:

* read: yes where needed for admin correction context
* create: no normal reporting create by default
* update: only through override flow where allowed
* submit: no normal reporting submit by default
* archive: no
* override: yes

Report Settings

Manager:

* no access

HoD:

* no access

Director:

* no access

Admin:

* full management access

Final Enforcement Rule

If there is any ambiguity:

* deny access by default
* preserve the strictest role boundary
* prefer read-only over edit access
* prefer own scope over division scope
* prefer division scope over company scope
* require explicit documentation before allowing broader access

Non-Negotiable Access Rules

Manager:

* own reporting only

HoD:

* own reporting plus own division monitoring and own division Big Rock management only

Director:

* monitoring only, no reporting workflow and no admin workflow

Admin:

* system management and override only, not automatically monitoring or reporting by default
