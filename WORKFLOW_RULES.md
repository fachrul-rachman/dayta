Workflow Rules

Core Principle

All workflows in this system must follow explicit business rules.
Do not simplify workflow timing.
Do not allow actions outside approved role scope.
Do not turn restricted workflows into free-form editing.
If a workflow is unclear, prefer the stricter implementation.

This project is a structured internal reporting and monitoring system.
Workflows must preserve:

* role boundaries
* timing boundaries
* historical integrity
* auditability
* business clarity

Workflow Categories

The system must support these workflow categories:

* authentication workflow
* Manager personal reporting workflow
* HoD personal reporting workflow
* HoD division monitoring workflow
* HoD Big Rock management workflow
* Director monitoring workflow
* Admin management workflow
* Admin override workflow
* AI summary generation workflow

1. Authentication Workflow

Purpose

Allow valid users to access the system according to their role.

Rules

* login is available only to registered active users
* user logs in with email and password
* role is determined by the user account, not selected manually on login
* after successful login, user is redirected to the correct role home page
* failed login must show a clear error
* unauthorized access to restricted pages must be blocked
* logout must end the session and redirect to login page

Role Redirect Rules

* Manager -> Manager Dashboard
* HoD -> HoD Dashboard
* Director -> Director Dashboard
* Admin -> Admin Home

Unauthorized Rules

* if a user tries to access a page outside their role, access must be denied
* access denial must happen at backend level, not only in UI
* user should see an unauthorized page or be redirected according to application policy

2. Reporting Date Workflow

Purpose

Define how the system identifies the reporting date used for plan and realization.

Rules

* reporting activity is tied to a business date
* plan and realization availability must refer to the configured report settings
* the application must not hardcode submission timing
* if report settings are missing or inactive, reporting workflows must not behave as open by default
* the UI must clearly indicate when reporting is unavailable due to missing settings

3. Plan Workflow

Purpose

Allow a reporting user to submit planned work for the reporting date.

Applicable Roles

* Manager
* HoD for own personal reporting only

Not Applicable To

* Director
* Admin by default

Plan Scope Rules

* a user can only create or update their own plan
* plan belongs to one user for one reporting date
* one daily entry header per user per reporting date should be maintained
* a plan may contain multiple plan items
* each plan item must use the defined work structure

Plan Availability States

Plan must support these business states:

* locked
* open
* draft
* submitted
* closed

State Meaning

locked

* plan cannot yet be filled because the allowed time window is not open

open

* user can work on plan input

draft

* user has saved work but has not completed submission

submitted

* user has submitted the plan

closed

* plan is no longer editable in the normal workflow

Plan Item Rules

Each plan item must support:

* work description
* work type / category
* optional Big Rock link

Allowed Work Type Direction

Use the approved work categories from project documents, such as:

* big_rock
* operational
* ad_hoc

Big Rock Rules for Plan

* Big Rock selection is optional
* Big Rock can only be selected when relevant
* only Big Rocks from the user’s division may be selectable
* only currently valid / allowed Big Rocks should appear in the selectable list
* the system must support work items with no Big Rock link

Plan Create / Edit Rules

When plan is open:

* user may add multiple items
* user may edit current items
* user may remove unsaved or allowed items depending on current state
* user may save draft if draft behavior is enabled
* user may submit plan

When plan is draft:

* user may continue editing while still within allowed workflow timing
* user may submit plan

When plan is submitted:

* normal editing should stop unless business rules explicitly allow limited revision during open state
* if no explicit revision rule is defined, treat submitted as read-only until admin override

When plan is closed:

* user cannot edit
* page should show read-only or closed state

When plan is locked:

* user cannot edit
* page should show locked state clearly

Plan Submission Rules

* submit action should validate required fields
* required items must not be empty when submit occurs
* optional draft behavior must not be mistaken for final submission
* submitted plan must update plan_status appropriately
* submission time should be captured if needed by implementation
* once submitted, plan becomes part of historical reporting context

Plan Validation Rules

At minimum:

* content must exist for submitted items
* work type must be valid
* Big Rock must belong to the same division if selected
* no invalid role may submit plan
* no user may submit another user’s plan

4. Realization Workflow

Purpose

Allow a reporting user to record what actually happened for the reporting date.

Applicable Roles

* Manager
* HoD for own personal reporting only

Not Applicable To

* Director
* Admin by default

Realization Scope Rules

* a user can only create or update their own realization
* realization belongs to the same daily entry context for that reporting date
* realization may contain multiple items
* realization must support structured explanation, not just free-form text dump

Realization Availability States

Realization must support these business states:

* locked
* open
* draft
* submitted
* closed

State Meaning

locked

* realization is not yet available

open

* realization can be entered or edited

draft

* realization has in-progress saved work

submitted

* realization has been submitted

closed

* realization is no longer editable in the normal workflow

Realization Item Rules

Each realization item must support the defined business structure, which may include:

* work description or linked/mapped work context
* work type / category
* optional Big Rock link
* completion status where applicable
* alignment-to-plan status where applicable
* reason where required

Alignment Rules

The system must support indicating whether realization aligns with the plan where the workflow requires this distinction.
If a realization item is marked as not aligned to plan, the required explanation must be enforced.

Completion Rules

Where completion status is used:

* user must be able to indicate completed vs not completed as required by the page design
* if an item is not completed and the workflow requires explanation, reason is mandatory

Reason Rules

Reason must be required when business logic demands explanation, such as:

* not aligned to plan
* not completed
* significant deviation from expected structure if defined

Big Rock Rules for Realization

* Big Rock linkage remains optional
* only valid Big Rocks from the appropriate division may be linked
* non-Big-Rock work must remain possible

Realization Create / Edit Rules

When realization is open:

* user may add or edit items
* user may save draft if draft behavior is enabled
* user may submit realization

When realization is draft:

* user may continue editing while within allowed timing
* user may submit realization

When realization is submitted:

* normal editing should stop unless explicit revision rules are later defined
* if no revision rule is defined, treat as read-only

When realization is closed:

* no normal editing allowed

When realization is locked:

* no editing allowed

Realization Submission Rules

* submit action should validate required structure
* required reasons must be enforced
* submitted realization must update realization_status appropriately
* realization becomes part of historical monitoring context

5. Daily Entry Parent Record Workflow

Purpose

Define how the system maintains the parent daily entry record.

Rules

* each user should have one daily entry record per reporting date
* that daily entry acts as the parent for plan and realization items
* plan and realization statuses must be trackable separately
* daily entry should preserve division context historically
* a user must never edit another user’s daily entry through normal workflows

Creation Rules

* daily entry may be created when user first interacts with reporting for a date
* if it already exists, the system should reuse the same parent record
* the system must not create duplicate parent records for the same user and reporting date

6. History Workflow

Purpose

Allow a reporting user to review historical reporting data in allowed scope.

Manager History Rules

* Manager can view only own history
* Manager can filter by allowed date inputs
* Manager may see plan summary, realization summary, statuses, and personal flags where applicable
* Manager must not see team or division reporting in history page

HoD Personal History Rules

* HoD personal history behaves like Manager personal history
* HoD history page is for own reporting only
* division team review must not be mixed into personal history

General History Rules

* history is read-only
* no normal editing from history view
* historical data should remain meaningful even when related master data later changes state
* if no results exist, show empty state

7. HoD Division Review Workflow

Purpose

Allow HoD to review team reporting for own division in read-only mode.

Scope Rules

* HoD may review only entries from own division
* review scope is read-only
* HoD must not edit subordinate entries
* HoD must not access other divisions’ team entries

Filter Rules

The page may support filters such as:

* date
* date range
* team member
* status
* entry type

Behavior Rules

* list must only show own division results
* detail view must remain read-only
* no admin override behavior should appear here
* if no results exist, show empty state

8. Big Rock Management Workflow

Purpose

Allow HoD to manage division-level Big Rocks.

Applicable Roles

* HoD only, and only for own division

Not Applicable To

* Manager
* Director
* Admin by default

Big Rock Create Rules

HoD may create a Big Rock for own division with the required fields:

* title
* applicable dates
* description if included
* status if the form supports it

Big Rock Edit Rules

* HoD may edit Big Rocks belonging only to own division
* edits must not break historical references
* if a Big Rock is already referenced by historical entries, it must remain historically meaningful

Big Rock Archive Rules

* prefer archive / deactivate over hard delete
* archived Big Rocks must not disappear from historical reporting meaning
* archived Big Rocks should generally not appear as selectable for new reporting unless business rules later allow it

Big Rock Visibility Rules

* Manager sees Big Rock only as a selectable option when filling relevant personal entries
* Director may see Big Rock in read-only monitoring context
* Admin does not manage Big Rock by default

9. Division Monitoring Workflow

Purpose

Allow HoD to monitor own division health.

Applicable Roles

* HoD only, for own division

Rules

* monitoring is limited to own division
* no cross-division access
* the page may include cards, trends, flags, and AI summary area
* all metrics must reflect the selected allowed filters
* no team entry editing is allowed from monitoring views

Summary Rules

* HoD may generate AI summary only for own division if summary generation is implemented
* AI summary must be treated as assistive narrative, not source of truth
* if no summary exists yet for the selected filter context, show empty summary state

10. Director Monitoring Workflow

Purpose

Allow Director to monitor company and division health in read-only scope.

Applicable Roles

* Director only

Not Applicable To

* Manager
* HoD
* Admin by default

Director Scope Rules

Director may access:

* Director Dashboard
* Company monitoring
* Division monitoring

Director may not access:

* personal reporting workflow
* Big Rock management workflow
* admin management workflow
* override workflow

Company Monitoring Rules

* company page is read-only
* company page may include cards, charts, flags, and AI summary output
* filters such as date range may be applied if supported
* no reporting form controls may appear

Division Monitoring Rules

* division page is read-only
* Director may select a division and filter date range
* division page may show charts, flags, alignment overview, and AI summary
* no division management controls may appear
* no Big Rock edit controls may appear

Director Summary Rules

* Director may generate AI summary for company scope if defined
* Director may generate AI summary for selected division scope if defined
* summaries must reflect active filter context

11. Flag / Finding Workflow

Purpose

Define how flags behave as monitoring outputs.

Rules

* flags are meaningful business outputs
* flags must support severity and scope
* flags must be visible in relevant monitoring and history contexts
* flags are not decorative only
* flags must not be replaced by AI summary

Scope Rules

Flags may apply to:

* personal reporting context where relevant
* division context
* company context
* daily entry context

Visibility Rules

* Manager may only see personal-scope flags when the page includes them
* HoD may see own division flags and allowed personal flags
* Director may see company and division flags in monitoring pages
* Admin may see flags only where needed for admin purpose, not as director dashboard by default

12. AI Summary Workflow

Purpose

Define how AI summary is generated and used.

Core Rule

AI summary is assistive only.
It does not replace structured reporting, flags, charts, or business metrics.

Allowed Summary Pages

* HoD Division Summary for own division
* Director Company page
* Director Division page

Not Allowed Summary Pages

* Manager pages
* Admin pages by default
* login / auth pages
* Big Rock management page
* override page

Generation Rules

* summary generation must respect role and scope restrictions
* summary generation should use the active page filter context where applicable
* generated summaries should be associated with their scope and filter range
* if integration is not yet active, the page may show an empty state or placeholder implementation, but must not invent unrelated AI behavior

Forbidden AI Behavior

Do not add:

* general AI chatbot
* auto-decision making
* AI scoring replacing structured flags
* AI-generated workflow changes
* AI editing of reporting data by itself

13. Report Settings Workflow

Purpose

Allow Admin to control plan and realization timing rules.

Applicable Roles

* Admin only

Rules

* settings determine reporting availability
* reporting availability must not be hardcoded
* if multiple settings records exist, one active settings context must control the system
* changes to settings affect future availability behavior according to implementation logic
* settings page is not available to non-admin roles

Settings Content Rules

Settings must support:

* plan timing
* realization timing
* timezone
* any required timing rule values defined by the project

Missing Settings Rules

* if settings are missing or inactive, reporting pages must not assume open availability
* UI should show clear unavailable state
* system should fail safely, not permissively

14. Admin User Management Workflow

Purpose

Allow Admin to manage users.

Applicable Roles

* Admin only

Rules

* Admin may create, edit, and deactivate users
* Admin may assign role only from the approved role list
* Admin may assign division where required by role
* unsupported roles must not be created
* history-sensitive users should be deactivated rather than destructively deleted

Role Assignment Rules

* manager requires division
* hod requires division
* director does not require division by default
* admin does not require division by default

Login Eligibility Rules

* inactive users should not be treated as active login users if auth policy blocks them

15. Admin Division Management Workflow

Purpose

Allow Admin to manage divisions.

Applicable Roles

* Admin only

Rules

* Admin may create, edit, and deactivate divisions
* do not hard delete divisions when historical reporting depends on them
* division history must remain meaningful

16. Admin HoD Assignment Workflow

Purpose

Allow Admin to assign the active HoD for each division.

Applicable Roles

* Admin only

Rules

* one division should have only one active HoD assignment at a time
* previous assignment history should be preserved
* assigned user must be a valid HoD user
* assignment must remain logically consistent with division context

17. Admin Override Workflow

Purpose

Allow Admin to perform controlled correction of reporting data.

Applicable Roles

* Admin only

Core Rule

Override is a controlled correction workflow, not a free edit mode.

Override Scope

Admin may override only through the dedicated override workflow.
Admin must not edit reporting data through normal user pages as if acting as Manager or HoD.

Override Rules

* target must be identifiable
* override reason is mandatory
* override action must be auditable
* who performed the override must be preserved
* what was changed should be traceable
* when it was changed should be preserved
* original business context should remain meaningful

Filter Rules

Override workflow may support filters such as:

* date
* date range
* division
* role
* user
* entry type

Behavior Rules

* Admin selects target
* Admin reviews current data
* Admin performs correction
* Admin provides reason
* System stores audit record
* corrected state becomes the current visible state according to implementation

Forbidden Behavior

* no override without reason
* no override by non-admin
* no silent overwrite without audit
* no destructive loss of historical context when traceability is required

18. Dashboard Workflow Rules

Purpose

Define the function of dashboard pages across roles.

General Dashboard Rule

Dashboards are orientation and shortcut pages.
They are not a license to add extra business actions.

Manager Dashboard Rules

* shortcut to personal reporting and history
* show personal status only

HoD Dashboard Rules

* shortcut to personal reporting and own division monitoring
* show division-level summary only for own division

Director Dashboard Rules

* shortcut to company and division monitoring
* show executive-level orientation only

Admin Home Rules

* shortcut to admin management modules
* show control summary, not Director monitoring by default

19. Data Preservation Workflow Rules

Purpose

Ensure historical integrity across workflows.

Rules

* reporting records are history-sensitive
* Big Rocks used in historical reporting should remain interpretable
* divisions with historical data should not be hard deleted
* users with historical records should not be hard deleted
* override actions must preserve auditability
* reporting context should remain meaningful even if master data later changes status

20. Strict Workflow Enforcement Rules

Default Rule When Unclear

If a workflow rule is unclear:

* prefer deny over allow
* prefer read-only over edit
* prefer own scope over wider scope
* prefer archive/deactivate over delete
* prefer auditability over silent mutation
* prefer explicit state handling over hidden assumptions

Non-Negotiable Workflow Rules

* role boundaries must never be blurred
* reporting timing must come from settings
* Manager and HoD report only for themselves
* HoD reviews team entries read-only
* Director is monitoring only
* Admin override requires reason and audit trail
* Big Rock is optional for reporting items
* AI summary is assistive, not authoritative
* history-sensitive data must be preserved

Do Not Do These

* do not let Director submit reporting
* do not let Manager or HoD edit other users’ entries
* do not let HoD access other divisions
* do not let Admin behave as Director by default
* do not hardcode reporting availability
* do not remove reason requirements where required
* do not use hard delete where historical integrity matters
* do not let AI replace monitoring rules or structured data

Final Workflow Intent

The workflow system must stay predictable, strict, and business-aligned.
The goal is not to maximize flexibility.
The goal is to preserve:

* clarity
* control
* auditability
* role separation
* operational trust
