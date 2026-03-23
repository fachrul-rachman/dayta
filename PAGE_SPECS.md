Page Specifications

Core Principle

Every page must have a clear business purpose.
Every page must belong to a defined role and must only contain actions allowed for that role.
Do not create pages outside this document.
Do not merge pages across roles unless explicitly defined here.
Do not invent extra sections, extra modules, or extra management features.

Global Layout Rules

Authenticated Layout

All authenticated pages must use a consistent internal application layout with:

* left sidebar
* top header
* main content area

Sidebar behavior:

* role-specific navigation only
* show only menu items allowed for the logged-in role
* active page must be visually clear
* no cross-role menu leakage

Top header behavior:

* page title
* optional page subtitle when useful
* user identity area
* logout action
* no unnecessary global search unless explicitly required later

Main content behavior:

* clean spacing
* business-focused layout
* clear section hierarchy
* cards, tables, filters, forms, and summaries must follow UI_SYSTEM.md

Global Page States

Pages should support applicable states where relevant:

* loading
* empty
* unauthorized
* locked
* open
* closed
* no data
* success
* validation error

Do not hide important states.
If a page or action is unavailable due to workflow timing or role restriction, the UI must communicate it clearly.

Public / Authentication Pages

Login Page

Page Purpose

Allow a registered user to log in and enter the system according to role.

Accessible By

* Manager
* HoD
* Director
* Admin

Main Sections

* login form card
* optional project title / subtitle
* validation / error message area

Form Fields

* email
* password

Primary Actions

* login

Behavior

* successful login redirects user to the correct role home page
* failed login shows a clear error
* do not expose role selection on login
* role is determined by the authenticated account

Route Outcome by Role

* Manager -> Manager Dashboard
* HoD -> HoD Dashboard
* Director -> Director Dashboard
* Admin -> Admin Home

Logout Action

Purpose

End the current session safely.

Accessible By

* all authenticated roles

Behavior

* logs user out
* redirects to login page

Unauthorized Page

Page Purpose

Show that the user attempted to access a page or action that is not allowed.

Accessible By

* any authenticated user when access is denied

Main Sections

* unauthorized message
* brief explanation
* button to return to allowed home page

Primary Actions

* back to home / dashboard

Manager Pages

Manager Dashboard

Page Purpose

Serve as the Manager’s orientation page and shortcut center for personal reporting activity.

Accessible By

* Manager only

Main Sections

* summary card section
* latest status / workflow card section
* personal shortcut section

Required Cards

* Today’s Date
* Plan Status Today
* Realization Status Today
* Latest History
* Personal Flags Summary if applicable

Card Intent

Today’s Date:

* shows current business date context

Plan Status Today:

* shows whether plan is locked, open, closed, draft, or submitted if applicable
* clicking this card goes to the relevant Daily Entry context

Realization Status Today:

* shows whether realization is locked, open, closed, draft, or submitted if applicable
* clicking this card goes to the relevant Daily Entry context

Latest History:

* shows latest submitted or latest available entry snapshot
* clicking this card goes to History

Personal Flags Summary:

* shows whether the manager has current or recent flags in personal scope if page design includes it
* clicking this card may go to History or personal detail area if defined consistently

Primary Actions

* open Daily Entry
* open History
* navigate from status cards

Behavior

* this page is not the primary place to fill forms
* it acts as a shortcut and orientation page
* cards must reflect real business state, not static placeholders
* if no history exists, show empty state

Daily Entry Page

Page Purpose

Allow Manager to work on today’s personal reporting through Plan and Realization.

Accessible By

* Manager only

Main Sections

* page header
* two entry cards or tabs for Plan and Realization
* entry detail area
* status / instruction area

Top Summary Area

Must show:

* current date
* plan status
* realization status
* brief workflow guidance where useful

Entry Mode Selector

There must be two clearly separated entry modes:

* Plan
* Realization

These may be shown as cards, tabs, or segmented controls, but both states must be clearly visible.

Plan Card / Selector

Shows:

* locked / open / closed / draft / submitted state where applicable
* when clickable, opens Plan form area
* if locked, must visibly communicate that it is unavailable

Realization Card / Selector

Shows:

* locked / open / closed / draft / submitted state where applicable
* when clickable, opens Realization form area
* if locked, must visibly communicate that it is unavailable

Plan Form Area

Page Purpose within Page

Allow user to define today’s planned work items.

Required Fields Per Item

* work item text / title
* work type or category according to business rules
* Big Rock selection when relevant and available
* no Big Rock selection required when item is not related to Big Rock

Plan Form Behavior

* user can add multiple plan items
* user can remove unsaved items
* user can edit current allowed items before submit/close based on workflow rules
* form must support zero-to-many entries only if business rules allow empty draft; if not, enforce minimum input for submit
* Big Rock options must only show allowed options for the user’s division

Plan Primary Actions

* add item
* remove item where allowed
* save draft if workflow allows
* submit plan

Realization Form Area

Page Purpose within Page

Allow user to record the realization of work for the relevant day.

Required Fields Per Item / Realization Record

* realized work item text or mapped work item
* completion state / aligned vs not aligned to plan as defined by workflow
* reason when required
* work category where needed by workflow
* Big Rock relation where relevant and allowed

Realization Behavior

* user can enter multiple realization items if required by business flow
* user must be able to indicate which work was completed and which was not completed according to the defined workflow model
* when a reason is mandatory, the form must enforce it
* realization must respect locked/open/closed timing from settings

Realization Primary Actions

* add item if allowed by workflow
* edit item while open
* remove unsaved item where allowed
* save draft if defined
* submit realization

Page-Level States

* if Plan is locked: show locked message and disable submit
* if Plan is open: allow interaction
* if Plan is closed: show read-only or closed state
* same logic for Realization
* when no Big Rock is available, the form must still allow non-Big-Rock work where applicable

History Page

Page Purpose

Allow Manager to review personal reporting history.

Accessible By

* Manager only

Main Sections

* filter area
* history results area

Filter Area

Required Filters

* single date and/or date range according to implementation choice
* optional status filter if consistent with product direction

History Result Area

Recommended Card Contents

Each history record should display:

* date
* plan summary
* realization summary
* submission status
* flag summary if applicable

Primary Actions

* apply filter
* reset filter
* open history detail if the implementation includes detail drilldown

Behavior

* only the logged-in manager’s own history may appear
* if no results exist, show empty state
* do not expose team or division entries

HoD Pages

HoD Dashboard

Page Purpose

Serve as the HoD’s orientation page and division control overview.

Accessible By

* HoD only

Main Sections

* own reporting status cards
* division monitoring summary cards
* Big Rock shortcut area
* summary shortcut area

Required Cards

* Today’s Date
* My Plan Status Today
* My Realization Status Today
* Team Submission Overview
* Division Flags Overview
* Big Rock Alignment Overview
* Big Rock Management Shortcut
* Division Summary Shortcut

Card Intent

My Plan Status Today:

* same purpose as manager personal reporting shortcut

My Realization Status Today:

* same purpose as manager personal reporting shortcut

Team Submission Overview:

* shows how many relevant team members have submitted vs not submitted if defined by data availability

Division Flags Overview:

* shows current or recent flag count or health signal for own division

Big Rock Alignment Overview:

* shows a concise view of how work is aligned to Big Rocks in own division

Big Rock Management Shortcut:

* navigates to Big Rock Management

Division Summary Shortcut:

* navigates to Division Summary

Behavior

* HoD dashboard combines personal reporting access and division oversight
* this page should not behave as an admin control panel
* all division data is limited to own division only

HoD Daily Entry Page

Page Purpose

Allow HoD to perform personal reporting the same way as Manager.

Accessible By

* HoD only

Structure

Same core structure and behavior as Manager Daily Entry, but within HoD role context.

Rules

* page behavior mirrors Manager daily entry for own entries
* HoD personal reporting must not be mixed with team editing
* HoD cannot edit subordinate entries from this page

HoD History Page

Page Purpose

Allow HoD to review personal reporting history.

Accessible By

* HoD only

Structure

Same core structure and behavior as Manager History, but within HoD role context.

Rules

* only own history is shown
* do not mix this page with division reporting review

Big Rock Management Page

Page Purpose

Allow HoD to manage Big Rocks for own division.

Accessible By

* HoD only

Main Sections

* page header
* filter or status view if useful
* Big Rock list
* create / edit form area or modal

Big Rock List Area

Each Big Rock record should show:

* name
* description if used
* start date
* end date
* status
* division context if useful for confirmation

Primary Actions

* create Big Rock
* edit Big Rock
* archive or deactivate Big Rock
* filter by status if included

Big Rock Form Fields

Required Fields

* Big Rock name
* Big Rock description if included
* start date
* end date
* status if directly editable in form

Behavior

* HoD can only manage Big Rocks for own division
* hard delete should not be the default behavior when history matters
* archived or inactive Big Rocks should remain historically meaningful
* if no Big Rocks exist, show empty state with create action

Division Entries / Review Team Reports Page

Page Purpose

Allow HoD to review team entries for own division in read-only scope.

Accessible By

* HoD only

Main Sections

* filter area
* team entry list or card view
* detail drilldown area or detail modal if implemented

Required Filters

* date or date range
* team member
* entry type where useful
* status where useful

Entry List / Card Area

Each item should show:

* user name
* date
* status
* concise plan/realisasi snapshot
* flags indicator if available

Primary Actions

* apply filter
* reset filter
* open detail

Behavior

* read-only only
* no editing subordinate entries
* own division only
* if no results exist, show empty state

Division Summary Page

Page Purpose

Allow HoD to monitor division health and generate division summary for own division.

Accessible By

* HoD only

Main Sections

* filter area
* division KPI / summary cards
* trend charts
* flag/findings area
* summary output area

Required Filters

* date range
* optional team member grouping only if consistent with product direction
* no cross-division filter

Required KPI / Summary Card Direction

The exact metrics may follow data availability, but the page must support business-oriented division monitoring such as:

* submission health
* flag count / severity overview
* Big Rock alignment overview
* workload category distribution if defined

Trend Charts

The page should support trend visualization for own division.
The exact chart set must remain consistent with business purpose and not become decorative.

Flag / Findings Area

Must show meaningful division warnings or findings according to the available rules and data.

Summary Output Area

Must include:

* AI summary output area
* Generate AI Summary action if allowed and implemented

Primary Actions

* apply filters
* reset filters
* generate AI summary for own division

Behavior

* all scope is restricted to own division
* if AI summary is not yet generated, show an empty summary state
* charts and summaries must reflect the active filters

Director Pages

Director Dashboard

Page Purpose

Serve as the Director’s executive overview and shortcut page.

Accessible By

* Director only

Main Sections

* company overview cards
* division highlight cards
* shortcut area to company and division pages

Required Cards

* Company Health Overview
* Company Flag Overview
* Division Requiring Attention
* Division Monitoring Shortcut
* Company Summary Shortcut

Card Intent

Company Health Overview:

* concise high-level signal for overall company condition

Company Flag Overview:

* shows total or summarized flags/findings in company scope

Division Requiring Attention:

* highlights one or more divisions with the strongest warning signal

Division Monitoring Shortcut:

* navigates to Division page

Company Summary Shortcut:

* navigates to Company page

Behavior

* this page is not a raw data page
* it should act as executive orientation and shortcut center
* card contents must reflect live system state where possible

Company Page

Page Purpose

Allow Director to monitor company-wide health.

Accessible By

* Director only

Main Sections

* filter area
* company KPI / summary cards
* trend charts
* flag/findings area
* AI summary output area

Required Filters

* date range
* optional grouping view if consistent with product direction
* no role-switching or admin filters

Required KPI / Summary Card Direction

The page must support business-focused company monitoring such as:

* submission health across organization
* flag overview
* division comparison summary
* Big Rock alignment at company monitoring level where relevant
* workload distribution where defined

Trend Charts

The page must support company-level trend visualization.

Flag / Findings Area

Must show meaningful company-level findings or rollups according to available rules.

AI Summary Output Area

Must include:

* summary output area
* Generate AI Summary action for company scope if implemented

Primary Actions

* apply filters
* reset filters
* generate AI summary
* navigate to division monitoring where relevant

Behavior

* page is read-only monitoring
* no daily entry controls
* no admin controls
* no Big Rock management controls

Division Page

Page Purpose

Allow Director to monitor division-level health across allowed divisions.

Accessible By

* Director only

Main Sections

* filter area
* division KPI / summary cards
* trend charts
* flags/findings area
* AI summary output area
* division detail emphasis area if useful

Required Filters

* division selector
* date range
* optional status or grouping filters if defined consistently

Required KPI / Summary Card Direction

The page must support division-level monitoring such as:

* selected division submission health
* selected division flag overview
* selected division Big Rock alignment overview
* selected division workload distribution if defined

Trend Charts

The page must support trend visualization for the selected division.

Flags / Findings Area

Must show the selected division’s important findings or warning signals.

AI Summary Output Area

Must include:

* summary output area
* Generate AI Summary action for selected division if implemented

Primary Actions

* select division
* apply filters
* reset filters
* generate AI summary
* open another division in monitoring scope

Behavior

* read-only monitoring only
* no Big Rock management controls
* no reporting form controls
* if no division is selected, show a clear prompt or default selection behavior

Admin Pages

Admin Home

Page Purpose

Serve as the Admin’s control center and shortcut page.

Accessible By

* Admin only

Main Sections

* admin summary cards
* shortcut cards to admin modules

Required Cards

* Active Users Overview
* Active Divisions Overview
* Active HoD Assignment Overview
* Report Settings Overview
* Override Shortcut
* User Management Shortcut
* Division Management Shortcut

Behavior

* page functions as an admin control home, not an executive business dashboard
* no reporting workflow controls as if admin were Manager or HoD
* no director-style company monitoring by default

User Management Page

Page Purpose

Allow Admin to manage users.

Accessible By

* Admin only

Main Sections

* filter/search area
* user list area
* create/edit user form area or modal
* optional bulk import area if later approved in scope

Required Filters

* role
* division
* status
* search by name/email if included

User List Columns / Card Contents

Must support viewing:

* name
* email
* role
* division when applicable
* status

Primary Actions

* create user
* edit user
* deactivate user
* filter users
* search users if enabled

User Form Required Fields

* name
* email
* role
* division when role requires division
* status if editable
* password fields where creation or reset flow requires them

Behavior

* role rules must be respected
* division assignment must respect business rules
* no unsupported roles may be created
* deactivate should be preferred over destructive deletion where history matters

Division Management Page

Page Purpose

Allow Admin to manage divisions.

Accessible By

* Admin only

Main Sections

* filter/search area if useful
* division list area
* create/edit division form area or modal

Division List Must Support

* division name
* status
* active HoD indication if useful

Primary Actions

* create division
* edit division
* deactivate division

Division Form Required Fields

* division name
* status if editable

Behavior

* do not hard delete divisions when history matters
* preserve historical integrity
* if no divisions exist, show empty state with create action

HoD Assignment Page

Page Purpose

Allow Admin to manage active HoD assignment per division.

Accessible By

* Admin only

Main Sections

* assignment list
* assign / reassign form area or modal
* current assignment status area

Assignment List Must Show

* division
* current active HoD
* assignment status
* effective context if used

Primary Actions

* assign HoD
* change active HoD
* update assignment as needed according to business rules

Behavior

* enforce business rule that a division should have only the allowed active HoD state at a time if defined
* preserve assignment integrity
* if there is no HoD for a division, show clear empty assignment state

Report Settings Page

Page Purpose

Allow Admin to manage report timing rules.

Accessible By

* Admin only

Main Sections

* settings form
* current active settings display
* save feedback area

Required Setting Inputs

* plan open rule or timing
* plan close / cutoff timing where applicable
* realization open rule or timing
* realization close / cutoff timing where applicable
* timezone if configurable
* related workflow timing values required by the business rules

Primary Actions

* save settings
* update settings

Behavior

* settings must drive reporting availability
* do not hardcode reporting time windows in the UI
* if settings are missing, affected workflow pages must reflect unavailable state clearly

Override Page

Page Purpose

Allow Admin to perform controlled override of reporting data with auditability.

Accessible By

* Admin only

Main Sections

* filter/search area
* override target result list
* target detail area
* override form area

Required Filters

* date
* date range if useful
* division
* role
* entry type such as plan or realization
* user if useful

Override Target List Must Show

* user
* role
* division
* date
* entry type
* current status

Override Detail Area Must Show

* existing entry data
* relevant status context
* relevant identity context

Override Form Requirements

* editable fields according to override scope
* required override reason
* save action
* cancel action

Primary Actions

* search / filter targets
* open target detail
* submit override with reason

Behavior

* override is controlled correction, not free-form unrestricted edit mode
* reason is mandatory
* original context and traceability must be preserved according to workflow and schema rules
* only Admin can access this page
* if no matching targets exist, show empty state

Cross-Page Rules

Date Filtering

Where a page supports historical or monitoring views, date or date range filters should be available when appropriate.
Date filters must be relevant to page purpose.
Do not add unnecessary filters.

Summary Generation Areas

Summary areas must exist only on the allowed pages:

* HoD Division Summary
* Director Company Page
* Director Division Page

Do not add summary generation to Manager pages.
Do not add summary generation to Admin pages unless future documents explicitly require it.

Big Rock Management Scope

Big Rock Management must exist only for HoD own division scope.
Do not place Big Rock management actions on Manager, Director, or Admin pages by default.

Read-Only Review Scope

Team entry review is only for HoD and only in own division scope.
Director uses aggregated monitoring pages, not subordinate reporting edit/review workflows.
Admin uses override workflow, not team reporting review workflow.

Page Naming Direction

Use clear business naming aligned with the product language.
Recommended page names:

* Dashboard
* Daily Entry
* History
* Big Rock Management
* Division Entries
* Division Summary
* Company
* Division
* Admin Home
* User Management
* Division Management
* HoD Assignment
* Report Settings
* Override

Do not invent alternative labels that change product meaning.

Definition of Page Completion

A page is complete only when:

* it is accessible only by the correct role
* it contains the required sections
* it supports the required states
* it supports the required actions
* it respects the workflow and access rules
* it does not expose out-of-scope controls
* it reflects the correct business purpose

Non-Negotiable Page Rules

Manager pages:

* personal reporting only

HoD pages:

* personal reporting plus own division oversight only

Director pages:

* monitoring only

Admin pages:

* control and configuration only

Do not blur these boundaries.
