Data Schema

Core Principle

The database schema must reflect the business model clearly and conservatively.
This schema is the contract for the application data structure.
Implementation may use Laravel migrations and Eloquent models, but the business meaning of each entity must remain intact.

Do not collapse important entities just for convenience.
Do not remove auditability where the product requires traceability.
Do not use hard delete for history-sensitive business data unless explicitly allowed.
Prefer explicit relationships, clear statuses, and preserved history.

Database Engine

Use PostgreSQL.

Schema Ownership

All schema changes must be implemented through Laravel migrations.
All application entities must be represented in a way that supports:

* strict role boundaries
* workflow timing rules
* historical traceability
* reporting and monitoring
* controlled override actions

Naming Direction

Use conventional, readable, plural table names.
Use snake_case for tables and columns.
Use foreign keys consistently.
Use created_at and updated_at timestamps unless there is a strong reason not to.
Use soft deletion only where explicitly justified.
Prefer status columns over deleting records when the business needs historical preservation.

Core Entity Overview

The schema must support these business domains:

* user accounts and role identity
* organizational divisions
* HoD assignment per division
* Big Rock planning per division
* daily reporting
* plan and realization item details
* flags / findings
* AI summary results
* report settings
* admin override auditability

Required Core Tables

1. users
2. divisions
3. division_hod_assignments
4. big_rocks
5. report_settings
6. daily_entries
7. daily_entry_items
8. flags
9. ai_summaries
10. admin_overrides

Optional supporting tables should only be added when clearly justified by implementation needs, and they must not change the business meaning of the core schema.

1. users

Purpose

Store all authenticated users in the system.

Supported Roles

Only these roles are allowed:

* manager
* hod
* director
* admin

Business Rules

* every user has exactly one role
* manager must belong to a division
* hod must belong to a division
* director must not require a division by default
* admin must not require a division by default
* email must be unique
* inactive users must remain historically meaningful if they have related records

Required Fields

* id
* name
* email
* password
* role
* division_id nullable
* is_active
* created_at
* updated_at

Recommended Field Notes

id

* primary key

name

* user display name

email

* unique login identity

password

* hashed password

role

* enum or constrained string
* allowed values only: manager, hod, director, admin

division_id

* foreign key to divisions
* required for manager and hod
* nullable for director and admin

is_active

* boolean active status
* inactive users may not log in depending on auth logic

Relationships

* belongs to division optionally
* has many daily_entries
* may appear in division_hod_assignments as assigned_hod_user_id
* may create admin override records as admin actor
* may be target of override through related reporting data

Constraints

* unique email
* role must be one of allowed roles
* division rule must be enforced in application logic and preferably validated consistently

2. divisions

Purpose

Store organizational divisions.

Business Rules

* division names should be unique or operationally unique
* divisions should not be hard deleted if historical data exists
* inactive divisions may remain in history

Required Fields

* id
* name
* is_active
* created_at
* updated_at

Relationships

* has many users
* has many Big Rocks
* has many daily_entries through users or direct division linkage
* has many division_hod_assignments

Recommended Notes

The active HoD should not be stored as a single static column if assignment history must be preserved.
Use a dedicated assignment table for integrity.

3. division_hod_assignments

Purpose

Track which HoD is assigned to which division, with support for assignment integrity and historical traceability.

Business Rules

* a division should have only one active HoD assignment at a time
* an assignment change must preserve previous assignment history
* assigned user must be a user with role = hod
* assigned user should belong to the same division or match the assignment rule enforced by the business logic

Required Fields

* id
* division_id
* hod_user_id
* is_active
* starts_at nullable if needed
* ends_at nullable if needed
* created_at
* updated_at

Relationships

* belongs to division
* belongs to user as hod_user_id

Constraints

* only one active assignment per division at a time
* hod_user_id must reference a valid HoD user
* history should be preserved when reassignment occurs

Recommended Implementation Direction

When a new active HoD assignment is created for a division, the prior active assignment should be ended or deactivated, not deleted.

4. big_rocks

Purpose

Store division-level strategic priorities used for planning alignment.

Business Rules

* Big Rocks belong to a single division
* Big Rocks are managed only by HoD for their own division
* Big Rocks may be active or archived
* archived Big Rocks must remain historically meaningful for existing entries
* not every daily entry item must be linked to a Big Rock

Required Fields

* id
* division_id
* title
* description nullable
* start_date
* end_date
* status
* created_at
* updated_at

Recommended Status Values

* active
* archived

Optional additional status such as draft may be introduced only if page and workflow documents explicitly require it.

Relationships

* belongs to division
* may be referenced by daily_entry_items

Constraints

* division_id required
* title required
* status required
* date range should be logically valid

Important Rule

Do not delete Big Rocks that are already referenced by reporting history.
Use archive/inactive behavior instead.

5. report_settings

Purpose

Store system-level timing rules that control when plan and realization are available.

Business Rules

* reporting availability must come from settings, not hardcoded values
* the system should use the active settings record
* if no valid settings exist, reporting workflows may need to be unavailable

Required Fields

* id
* timezone
* plan_open_rule or plan_open_time
* plan_close_rule or plan_close_time
* realization_open_rule or realization_open_time
* realization_close_rule or realization_close_time
* is_active
* created_at
* updated_at

Implementation Direction

The exact representation of timing may be:

* timestamp-style rules
* time-only values
* structured offset values

But the schema must clearly support:

* plan availability
* realization availability
* configurable timing
* active settings selection

Relationships

This table may stand alone as configuration data.

Constraints

* only one settings record should be active at a time if multiple versions are stored
* timezone must be valid according to implementation rules

Recommended Note

If versioning of settings is useful, preserve prior settings rather than overwriting without trace.

6. daily_entries

Purpose

Store the main daily reporting record for a user on a given reporting date.

Business Meaning

This is the parent record for one user’s reporting context for one date.
It groups plan and realization item data for that date.

Business Rules

* daily entry belongs to one user
* reporting date is required
* entry should reflect status needed for workflow
* one user should not have duplicate daily entry headers for the same reporting date unless the business explicitly allows it

Required Fields

* id
* user_id
* division_id
* entry_date
* plan_status
* realization_status
* created_at
* updated_at

Recommended Status Values

plan_status:

* locked
* open
* draft
* submitted
* closed

realization_status:

* locked
* open
* draft
* submitted
* closed

Relationships

* belongs to user
* belongs to division
* has many daily_entry_items
* has many flags if flags are attached at entry scope
* may be referenced by admin_overrides

Constraints

* user_id required
* division_id required
* entry_date required
* unique constraint recommended on user_id + entry_date
* division_id should align with the user’s division when applicable

Recommended Note

Storing division_id directly in daily_entries is useful for historical consistency even if the user later changes division.

7. daily_entry_items

Purpose

Store the individual items inside a daily entry for both plan and realization.

Business Meaning

Each daily entry may contain multiple item rows.
Items represent the actual structured work records that power reporting, monitoring, flags, and summaries.

Business Rules

* item belongs to one daily entry
* item type must distinguish plan vs realization or use another clear model that preserves both concepts
* item may or may not link to a Big Rock
* item must support work categorization
* realization items may require explanation when not aligned to plan
* structure must preserve traceability of what was planned and what was realized

Required Fields

* id
* daily_entry_id
* item_type
* work_type
* content
* big_rock_id nullable
* is_completed nullable where relevant
* is_aligned_to_plan nullable where relevant
* reason nullable
* sort_order nullable
* created_at
* updated_at

Recommended Item Type Values

* plan
* realization

Recommended Work Type Values

Use explicit allowed values according to business rules, for example:

* big_rock
* operational
* ad_hoc

Relationships

* belongs to daily_entry
* optionally belongs to big_rock

Field Notes

content

* the main description of the work item

big_rock_id

* nullable because not all work maps to Big Rock

is_completed

* useful for realization if the workflow requires completion state

is_aligned_to_plan

* useful for realization if the workflow tracks whether actual work matches the plan

reason

* required in cases defined by workflow rules, such as not aligned or not completed scenarios

sort_order

* optional helper for preserving UI order

Constraints

* daily_entry_id required
* item_type required
* work_type required
* content required
* big_rock_id nullable
* reason must be enforced by application logic when required

Recommended Modeling Direction

Keep plan and realization items in one table if item_type clearly separates them and the business rules remain readable.
Do not split into separate tables unless there is a strong implementation reason.

8. flags

Purpose

Store rule-based findings or warning signals generated from reporting behavior or data patterns.

Business Meaning

Flags are meaningful monitoring outputs, not decorative labels.
They may apply to a daily entry, a user, a division, or a broader summary context depending on implementation direction.
The schema must support traceability of what was flagged and why.

Business Rules

* flags should preserve severity and meaning
* flags should remain auditable
* flags should support monitoring pages and summary pages
* AI summary must not replace flags

Required Fields

* id
* scope_type
* scope_id
* flag_type
* severity
* title
* description nullable
* flagged_at
* created_at
* updated_at

Recommended Scope Types

* daily_entry
* user
* division
* company

Recommended Severity Values

* low
* medium
* high

Relationships

Because flags may apply to different scopes, this can be modeled as a polymorphic scope:

* scope_type
* scope_id

Alternative implementation is allowed if the same business meaning is preserved.

Field Notes

flag_type

* structured business category of finding

title

* short readable label

description

* optional detail

flagged_at

* business timestamp of when flag was generated

Important Rule

Do not reduce flags to a simple boolean if severity and type matter for monitoring.

9. ai_summaries

Purpose

Store generated AI summaries for allowed monitoring scopes.

Business Meaning

AI summaries are generated outputs for reading conditions in business language.
They are not the source of truth.
They should be stored in a way that preserves scope and filtering context.

Business Rules

* summaries only exist for allowed roles and allowed scopes
* summaries may exist for division scope or company scope
* summaries should reflect a filter context such as date range
* summaries should not replace dashboard metrics or flags

Required Fields

* id
* scope_type
* scope_id nullable when company scope does not need a specific id
* generated_by_user_id nullable
* summary_text
* date_from nullable
* date_to nullable
* generated_at
* created_at
* updated_at

Recommended Scope Types

* division
* company

Relationships

* may belong to a user as generator
* may reference division depending on scope
* company scope may not need a separate company table if the product assumes one organization

Field Notes

summary_text

* generated narrative output

generated_by_user_id

* useful for audit and context

date_from and date_to

* capture filter scope used to generate summary

Important Rule

Do not store AI summaries without scope context if filter-based generation is supported.

10. admin_overrides

Purpose

Store audit records for admin correction actions.

Business Meaning

Override is a controlled correction workflow.
It must preserve who changed what, why, and when.
This table is about auditability, not only the final corrected value.

Business Rules

* override must be admin-only
* override reason is mandatory
* override must preserve traceability
* target of override must be identifiable

Required Fields

* id
* admin_user_id
* target_type
* target_id
* field_name nullable if field-level override tracking is used
* old_value nullable
* new_value nullable
* reason
* overridden_at
* created_at
* updated_at

Recommended Target Types

* daily_entry
* daily_entry_item

Relationships

* belongs to user as admin actor
* references target record through polymorphic target fields or equivalent approach

Field Notes

field_name

* useful if override is tracked at field level

old_value

* should preserve prior value where practical

new_value

* should preserve new value where practical

reason

* mandatory explanation by admin

overridden_at

* business timestamp of the override action

Important Rule

Do not implement override without an auditable record.

Recommended Additional Supporting Tables

These are not mandatory by default, but may be added if needed without changing core business meaning.

Possible Supporting Table: password_reset_tokens or framework auth tables

* allowed as part of Laravel auth

Possible Supporting Table: sessions

* allowed as part of Laravel session auth

Possible Supporting Table: jobs / failed_jobs / cache tables

* allowed as standard framework infrastructure

Possible Supporting Table: activity_logs

* allowed only if used carefully and not as a replacement for required domain audit tables like admin_overrides

Relationships Summary

users

* belongs to divisions optionally
* has many daily_entries
* may have many division_hod_assignments as HoD
* may have many admin_overrides as admin actor
* may generate ai_summaries

divisions

* has many users
* has many big_rocks
* has many division_hod_assignments
* may be the scope of ai_summaries
* may be the scope of flags
* may be referenced by daily_entries

division_hod_assignments

* belongs to divisions
* belongs to users as HoD

big_rocks

* belongs to divisions
* may be used by daily_entry_items

daily_entries

* belongs to users
* belongs to divisions
* has many daily_entry_items
* may have flags
* may be targeted by admin_overrides

daily_entry_items

* belongs to daily_entries
* optionally belongs to big_rocks
* may be targeted by admin_overrides

flags

* belong to a scope through scope_type and scope_id or equivalent design

ai_summaries

* belong to a scope through scope_type and scope_id or equivalent design
* may belong to generator user

admin_overrides

* belong to admin actor user
* target a specific record or field context

Business Constraints That Must Be Reflected

User and Division Rules

* manager requires division
* hod requires division
* director does not require division by default
* admin does not require division by default
* only one active HoD assignment per division at a time

Daily Reporting Rules

* one daily entry header per user per date
* plan and realization states must be representable
* daily entry items must support multiple rows
* Big Rock link is optional
* non-Big-Rock work must be supported
* reason fields must be possible where workflow requires explanation

Monitoring Rules

* flags must support severity and scope
* division and company summaries must support filter context
* metrics and summaries depend on historical data integrity

Administrative Rules

* report settings control availability
* override must preserve who, what, why, and when
* history-sensitive entities should not be hard deleted

Status and Enum Direction

Use enums or strongly validated string columns for important controlled values.

Recommended controlled values include:

* users.role
* big_rocks.status
* daily_entries.plan_status
* daily_entries.realization_status
* daily_entry_items.item_type
* daily_entry_items.work_type
* flags.scope_type
* flags.severity
* ai_summaries.scope_type
* admin_overrides.target_type

Do not use free-text values for these controlled business dimensions unless there is a strong reason.

Deletion and Preservation Rules

Prefer the following:

* users: deactivate rather than delete
* divisions: deactivate rather than delete
* big_rocks: archive rather than delete
* daily_entries: preserve
* daily_entry_items: preserve when submitted/history-sensitive
* flags: preserve for audit/monitoring history
* ai_summaries: preserve as generated history unless retention policy is later defined
* admin_overrides: preserve

Migration Guidance

Migration files should:

* create core tables clearly
* define foreign keys where appropriate
* define uniqueness where appropriate
* define indexes for common filters where appropriate
* avoid destructive assumptions
* preserve future extensibility without changing core business meaning

Recommended Index Direction

Consider indexes for:

* users.email
* users.role
* users.division_id
* divisions.name
* division_hod_assignments.division_id
* division_hod_assignments.hod_user_id
* big_rocks.division_id
* big_rocks.status
* report_settings.is_active
* daily_entries.user_id
* daily_entries.division_id
* daily_entries.entry_date
* unique daily_entries user_id + entry_date
* daily_entry_items.daily_entry_id
* daily_entry_items.item_type
* daily_entry_items.work_type
* daily_entry_items.big_rock_id
* flags.scope_type + scope_id
* flags.severity
* flags.flagged_at
* ai_summaries.scope_type + scope_id
* ai_summaries.date_from + date_to where useful
* admin_overrides.target_type + target_id
* admin_overrides.admin_user_id
* admin_overrides.overridden_at

Non-Negotiable Schema Rules

* use PostgreSQL
* use Laravel migrations
* preserve history where business meaning depends on it
* enforce strict role model
* support division-based reporting
* support Big Rock optional linkage
* support plan and realization separately
* support flags with severity and scope
* support AI summaries with scope context
* support admin override audit trail

Do Not Do These

* do not hardcode reporting rules into the schema without settings support
* do not merge all business records into a generic JSON blob table
* do not use a single catch-all table for monitoring outputs
* do not remove division context from records that need historical consistency
* do not design override as an unaudited edit
* do not model flags as a simple yes/no column if severity and type matter
* do not make Big Rock mandatory for every work item
* do not assume director or admin require division by default

Final Schema Intent

The schema must be simple enough to implement cleanly in Laravel, but explicit enough to protect the business model.
It must support:

* strict roles
* structured reporting
* division monitoring
* company monitoring
* Big Rock alignment
* rule-based findings
* AI summaries
* admin configuration
* auditable override behavior

The schema is not meant to be overly complex, but it must be precise enough that Codex cannot improvise core data structure incorrectly.
