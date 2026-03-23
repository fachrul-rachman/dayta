UI System and Copy Guidelines

Core UI Direction

The product must feel modern, premium, calm, and structured.
The visual style should be clean and business-focused, not playful, noisy, or template-heavy.
Use a bento-card dashboard style across all role home pages and summary-heavy pages.

The reference direction is:

* rounded container layout
* soft surface contrast
* spacious internal padding
* card-based hierarchy
* strong visual grouping
* clear data priority
* light modern executive-dashboard feel

This system is not a traditional legacy admin panel.
It should look like a modern internal product.

Mobile-First Principle

The UI must be designed mobile first.
Start from the smallest screen and progressively enhance for tablet and desktop.
Do not design desktop first and then squeeze it down.

This means:

* content priority must be clear on mobile
* single-column stacking should be the default
* actions must remain reachable with one hand when possible
* spacing must remain comfortable on small screens
* cards must remain readable without requiring zoom
* forms must remain easy to complete on mobile
* tables must degrade gracefully into stacked or scrollable patterns

Desktop is still an important target for daily use, but the structure must begin from mobile logic.

Design Personality

Use this UI personality consistently:

* modern
* polished
* minimal
* executive
* soft
* structured
* trustworthy
* readable

Avoid:

* dense enterprise clutter
* harsh borders everywhere
* old-style table-heavy admin feel
* neon colors
* too many accent colors
* overuse of badges
* visual noise
* exaggerated gradients
* overly playful empty states

Overall Layout System

Authenticated App Shell

All authenticated pages must use a consistent shell with:

* mobile navigation pattern first
* adaptive sidebar for larger screens
* top header
* main content canvas
* generous spacing
* rounded application frame feel on tablet and desktop

Layout Structure

Mobile first:

* top header
* compact navigation access
* stacked content sections
* single-column content by default
* cards arranged vertically

Tablet and desktop enhancement:

* rounded app frame
* left sidebar inside the main frame
* larger header aligned to content
* multi-column bento sections where appropriate

Use spacing generously.
Do not compress cards too tightly.

Container Direction

The application should visually feel like:

* one elevated internal workspace
* with cards placed inside it
* with sections clearly separated through spacing, not heavy borders

On mobile, the workspace should still feel clean and premium, but simpler and more linear.

Responsive Layout Rules

Mobile

* single-column layout by default
* cards stacked vertically
* filters shown in collapsible or wrapped layout
* forms shown in vertical flow
* tables converted to card list or horizontal scroll only when necessary
* sidebar replaced by drawer or bottom-access pattern

Tablet

* introduce two-column sections where useful
* sidebar may become visible or semi-collapsed
* cards may begin using mixed bento width

Desktop

* full sidebar visible
* full bento dashboard layout
* larger chart cards
* tables shown in full-width desktop style where appropriate

Sidebar System

Sidebar Purpose

The sidebar is the primary navigation anchor on tablet and desktop.
On mobile, navigation should become a compact drawer or sheet.

Sidebar Style

* vertical navigation
* rounded internal panel feel
* minimal icon + label navigation items
* active item should have a strong pill or soft-highlight state
* inactive items should remain calm and readable
* bottom utility area for account or logout if useful

Sidebar Behavior

* show only menu items allowed for that role
* do not show cross-role items
* active state must be obvious
* hover states should be subtle and polished on larger screens
* on mobile, the navigation should open from a menu button in the header

Mobile Navigation Rules

Preferred mobile behavior:

* top-left menu button
* slide-out navigation drawer
* clear page title in header
* no permanently fixed sidebar taking screen width

Do not use bottom tab navigation because the menu count varies too much by role and some admin flows are too deep for a tab model.

Sidebar Copy Rules

Use short labels only.
Do not use technical or verbose menu labels.

Approved Sidebar Labels

Manager

* Dashboard
* Daily Entry
* History

HoD

* Dashboard
* Daily Entry
* History
* Big Rocks
* Division Entries
* Division Summary

Director

* Dashboard
* Company
* Divisions

Admin

* Admin Home
* Users
* Divisions
* HoD Assignment
* Report Settings
* Override

Do not invent alternate names unless explicitly approved later.

Top Header System

Header Purpose

The header should orient the user quickly without feeling crowded.

Header Contents

* page title
* optional short subtitle
* optional contextual action area on the right
* user profile area
* logout or account menu
* mobile navigation trigger on small screens

Header Style

* clean
* light
* spacious
* horizontally balanced
* not overloaded with controls

Do not add:

* global search by default
* too many icons
* notification center unless explicitly needed later
* breadcrumb overload unless page depth truly requires it

Header Behavior by Screen Size

Mobile

* page title
* optional small subtitle or none
* menu button
* minimal right-side actions only

Tablet/Desktop

* fuller header layout
* optional subtitle
* more room for page-level actions

Page Titles

Use strong, plain-English page titles.

Approved Page Titles

Manager

* Dashboard
* Daily Entry
* History

HoD

* Dashboard
* Daily Entry
* History
* Big Rocks
* Division Entries
* Division Summary

Director

* Dashboard
* Company Overview
* Division Overview

Admin

* Admin Home
* User Management
* Division Management
* HoD Assignment
* Report Settings
* Override

Optional Page Subtitle Style

If a subtitle is used, keep it short and useful.

Examples:

* Track your reporting progress and recent updates.
* Review your division’s reporting health and priorities.
* Monitor company-wide trends and key findings.
* Manage system settings and operational controls.

Bento Card System

Core Principle

Bento cards are the main visual language of the product.
Cards must feel modular, elegant, and intentional.
They are not random boxes.
Each card should communicate one clear purpose.

Card Style Direction

All cards should generally have:

* large rounded corners
* soft background contrast
* subtle shadow or elevation
* strong internal spacing
* clean typography hierarchy
* minimal border usage
* enough breathing room

Card Content Structure

A card should usually contain:

* eyebrow or small label
* primary value or status
* small support text
* optional action or link
* optional icon

Card Types

1. Status Card
   Used for:

* Plan Status
* Realization Status
* Submission Overview
* Flags Overview
* Report Settings Overview

2. Metric Card
   Used for:

* Total Open Flags
* Submitted Entries
* Active Users
* Active Divisions

3. Shortcut Card
   Used for:

* Go to Daily Entry
* Open History
* Open Big Rocks
* Open Override

4. Trend Card
   Used for:

* chart containers
* trend summaries
* division health trends
* workload distribution

5. Summary Card
   Used for:

* AI Summary output
* key findings
* highlights
* important notices

Card Density Rules

Do not overload one card with too much information.
If content becomes too dense, split it into multiple cards.
Bento layout works best when each card has one strong focus.

Card Hierarchy Rules

Some cards should feel larger and more important than others.
Use size to guide attention.

Recommended visual hierarchy:

* hero or summary card
* key metric cards
* charts
* supporting list or table card

Mobile Card Rules

On mobile:

* every card must remain readable in a single column
* card padding must stay generous but not oversized
* long text should wrap cleanly
* actions should not crowd the top-right corner
* important status should appear near the top of the card
* avoid placing too many mini-metrics in one card

Dashboard Layout Direction

All dashboards should use bento-style section layouts.

General pattern:

* greeting or orientation row
* key shortcut or status cards
* larger insight cards
* supporting detail cards
* lower section for history, table, or list if needed

Mobile-first dashboard pattern:

* stack all cards vertically first
* place the most important action/status cards first
* charts appear after primary status cards
* supporting lists appear last

Manager Dashboard

Should feel personal and action-oriented.

Recommended card composition:

* Today
* Plan Status
* Realization Status
* Latest History
* Personal Flags
* Optional small guidance card

HoD Dashboard

Should feel like personal reporting plus division oversight.

Recommended card composition:

* My Plan Status
* My Realization Status
* Team Submission
* Division Flags
* Big Rock Alignment
* Big Rocks shortcut
* Division Summary shortcut

Director Dashboard

Should feel executive and observational.

Recommended card composition:

* Company Health
* Company Flags
* Division Requiring Attention
* Company Overview shortcut
* Division Overview shortcut
* Optional top findings card

Admin Home

Should feel like a clean operations hub.

Recommended card composition:

* Active Users
* Active Divisions
* HoD Assignment Status
* Report Settings Status
* Users shortcut
* Divisions shortcut
* Override shortcut

Grid Rules

Use mixed-size bento grids.
Do not force every card into identical boxes.
Allow larger cards for:

* charts
* summaries
* priority findings
* large shortcuts

Use smaller cards for:

* quick metrics
* statuses
* compact actions

Responsive Grid Rules

Mobile

* 1 column default
* no micro-cards squeezed side by side unless there is enough safe width
* large hero card may still remain single column

Tablet

* 2 columns by default for card sections
* some cards can span full width

Desktop

* 3 to 4 column rhythm where appropriate
* featured cards may span multiple columns or rows

Forms

Form Style Direction

Forms should feel simple, modern, and focused.
Do not make forms look like old enterprise forms.

Form Design Rules

* use clear labels above inputs
* keep field spacing generous
* use grouped sections when useful
* use large enough input height for comfort
* use modern rounded input styling
* keep validation clear and specific
* avoid crowded inline form layouts unless space requires it

Form Mobile Rules

* single-column form flow by default
* labels always above fields
* actions stacked or wrapped when needed
* sticky bottom action bar may be used on mobile for primary actions if implemented cleanly
* avoid placing too many fields in one row

Form Section Titles

Use plain labels such as:

* Plan Details
* Realization Details
* Basic Information
* Assignment Details
* Report Window Settings
* Override Details

Field Label Style

Labels must be direct and natural.
Do not sound robotic or technical.

Examples:

* Work Item
* Work Type
* Related Big Rock
* Reason
* Start Date
* End Date
* Role
* Division
* Status

Avoid labels like:

* Enter Work Item Description Here
* Please Select Your Applicable Division
* Type of Reporting Category

Input Copy Direction

Use concise, natural placeholder text.

Examples:

* Describe the work item
* Select a work type
* Select a Big Rock if relevant
* Add a short reason
* Choose a date range
* Search by name or email

Buttons

Button Style Direction

Buttons should feel modern, clear, and slightly bold.
Primary actions should stand out without feeling aggressive.

Button Types

Primary Button
Used for:

* Submit Plan
* Submit Realization
* Save Changes
* Generate Summary
* Create User
* Save Settings

Secondary Button
Used for:

* Save Draft
* Cancel
* Back
* Reset Filter

Tertiary / Ghost Button
Used for:

* View Details
* Open History
* Open Division
* Remove Item

Danger Button
Used carefully for:

* Deactivate
* Archive
* Confirm Override if danger styling is appropriate

Button Copy Rules

Use short, action-first English labels.

Approved Action Labels

General

* Save
* Save Draft
* Submit
* Cancel
* Update
* Create
* Edit
* Archive
* Deactivate
* Back
* Reset
* Apply Filter
* Clear Filter
* View Details
* Open
* Generate Summary

Manager / HoD Reporting

* Add Item
* Remove Item
* Submit Plan
* Submit Realization
* Save Draft

Big Rocks

* Create Big Rock
* Edit Big Rock
* Archive Big Rock

Director / Summary

* Generate Summary
* View Company Overview
* View Division Overview

Admin

* Create User
* Update User
* Create Division
* Update Division
* Assign HoD
* Save Settings
* Submit Override

Do not use robotic button labels like:

* Proceed
* Execute
* Confirm Operation
* Run AI Process

Button Mobile Rules

* primary buttons should be easy to tap
* avoid placing too many equal-priority buttons on one row
* on mobile, stack or wrap actions when needed
* destructive actions should stay visually separated from primary actions

Tables

Table Style Direction

Tables must feel modern and light.
Avoid thick borders and old-style grid-heavy tables.

Use tables mainly for:

* User Management
* Division Management
* HoD Assignment
* History results if needed
* Override target results

Table Rules

* soft row separation
* readable line height
* clean header styling
* actions aligned consistently
* status chips must be subtle and readable
* avoid showing too many columns at once

Mobile Table Rules

On mobile, do not force full desktop tables into narrow widths.
Use one of these patterns:

* stacked record cards
* horizontally scrollable table for secondary detail
* expandable row cards

Prefer stacked card records for admin and history views when readability matters more than dense comparison.

Table Column Copy Examples

Users

* Name
* Email
* Role
* Division
* Status
* Actions

Divisions

* Division
* Active HoD
* Status
* Actions

HoD Assignment

* Division
* Assigned HoD
* Status
* Effective Period
* Actions

Override

* User
* Role
* Division
* Date
* Entry Type
* Status
* Actions

Charts

Chart Direction

Charts should feel polished, business-focused, and easy to read.
They must support insight, not decoration.

Chart Rules

* use clean axes and spacing
* use restrained colors
* avoid 3D effects
* avoid visual clutter
* use one consistent chart language throughout the app

Recommended Chart Types

* line charts for trend over time
* bar charts for category comparison
* stacked bar charts for workload mix if useful
* donut charts only when truly helpful and not overused

Chart Card Titles

Examples:

* Submission Trend
* Flag Trend
* Workload Distribution
* Big Rock Alignment
* Division Health Trend
* Company Reporting Trend

Mobile Chart Rules

* charts must remain legible on small screens
* reduce legend clutter
* avoid overloading one chart with too many series
* use shorter labels when needed
* allow chart card to become taller rather than cramped
* if a chart becomes unreadable on mobile, replace it with a simplified version or a summary card

Filters

Filter UI Direction

Filters should be compact, clean, and easy to scan.
Do not overbuild them.

Filter Components May Include

* date picker
* date range picker
* division selector
* user selector
* role selector
* status selector
* entry type selector

Filter Button Labels

* Apply Filter
* Reset Filter
* Clear
* Select Division
* Select Date Range

Mobile Filter Rules

* stack filters vertically by default
* allow filters to wrap cleanly
* use collapsible filter section if a page has many controls
* keep primary filter action visible without clutter

Empty States

Empty states should sound calm, helpful, and professional.
Do not use playful or cheesy language.

Approved Empty State Copy Examples

General

* No data available yet.
* No results found for the selected filters.
* Nothing to show here yet.

History

* No history found for the selected date range.

Big Rocks

* No Big Rocks have been created for this division yet.

Division Entries

* No entries match the current filters.

Summary

* No summary has been generated for this selection yet.

Admin

* No records found.
* No active assignment found for this division.

Locked / Closed / Status Messaging

These messages are important and must be clear.

Locked State Copy

* This section is not available yet.
* Plan entry is currently locked.
* Realization entry is currently locked.

Open State Copy

* This section is available.
* Plan entry is open.
* Realization entry is open.

Closed State Copy

* This section is closed.
* Plan entry is closed.
* Realization entry is closed.

Submitted State Copy

* Plan submitted
* Realization submitted
* Submitted successfully

Draft State Copy

* Draft saved
* This entry is still in draft
* You can continue before submission closes

Unauthorized Copy

Use direct, calm language:

* You do not have access to this page.
* Your account does not have permission to view this content.

Validation Copy

Validation messages must be natural and specific.

Examples:

* Please enter a work item.
* Please select a work type.
* Please provide a reason.
* Please select a division.
* Please select a valid date range.
* This field is required.

Success Copy

Examples:

* Changes saved successfully.
* Plan submitted successfully.
* Realization submitted successfully.
* Big Rock created successfully.
* Big Rock updated successfully.
* User created successfully.
* Settings updated successfully.
* Override submitted successfully.

Role-Specific Copy Guidelines

Manager Tone

Copy should feel direct and simple.
Focus on personal action.

Examples:

* Today’s Plan
* Today’s Realization
* Recent History
* Personal Flags
* Complete your reporting for today

HoD Tone

Copy should feel clear and operational.
Focus on team and division context.

Examples:

* Team Submission Status
* Division Flags
* Big Rock Alignment
* Review your division’s reporting health

Director Tone

Copy should feel executive and concise.
Focus on high-level insight.

Examples:

* Company Health Overview
* Division Requiring Attention
* Key Findings
* Monitor overall reporting performance

Admin Tone

Copy should feel operational and controlled.
Focus on configuration and management.

Examples:

* Manage system users and assignments
* Update report timing rules
* Review and correct reporting data
* System control and configuration

Status Chips and Labels

Recommended Status Labels

General

* Active
* Inactive
* Draft
* Submitted
* Locked
* Open
* Closed
* Archived

Flags

* Low
* Medium
* High

Entry Types

* Plan
* Realization

Work Types

* Big Rock
* Operational
* Ad Hoc

Roles

* Manager
* HoD
* Director
* Admin

Copy for Core UI Elements

Dashboard Card Titles

Manager

* Today
* Plan Status
* Realization Status
* Latest History
* Personal Flags

HoD

* Today
* My Plan Status
* My Realization Status
* Team Submission
* Division Flags
* Big Rock Alignment

Director

* Company Health
* Company Flags
* Division Requiring Attention
* Company Overview
* Division Overview

Admin

* Active Users
* Active Divisions
* HoD Assignment
* Report Settings
* Override

Section Titles

Reporting

* Plan Details
* Realization Details
* Reporting History

Monitoring

* Key Metrics
* Trend Overview
* Flag Summary
* Summary Output

Admin

* User Directory
* Division Directory
* Assignment Details
* Override Details
* Current Settings

Modal Titles

Examples:

* Create Big Rock
* Edit Big Rock
* Create User
* Edit User
* Create Division
* Edit Division
* Assign HoD
* Confirm Archive
* Confirm Deactivation
* Submit Override

Modal Body Copy Examples

Archive

* This Big Rock will be archived and will no longer be available for new entries.

Deactivate User

* This user will be deactivated and will no longer be able to access the system.

Deactivate Division

* This division will be deactivated. Historical data will remain available.

Override

* Please review the current data and provide a reason before submitting the override.

Search and Filter Copy

Search Input Placeholders

* Search by name
* Search by name or email
* Search records
* Search division

Filter Labels

* Role
* Division
* Status
* Entry Type
* Date
* Date Range
* Team Member

Summary Area Copy

Summary Section Title

* Summary
* AI Summary

Summary Empty State

* No summary has been generated yet.

Summary Action

* Generate Summary

Summary Helper Copy

* Generate a summary based on the current selection.
* The summary reflects the active filters on this page.

Copy Rules for AI Summary

Do not use overly “AI” language.
Do not make it sound gimmicky.

Preferred labels:

* Summary
* AI Summary
* Generate Summary

Avoid:

* Smart AI Assistant
* Ask AI
* Magic Summary
* Auto Insight Engine

Visual Rhythm Rules

Use a strong vertical rhythm across pages:

* header block
* summary cards
* insight cards
* detail blocks
* tables or forms

Keep whitespace intentional.
Let the interface breathe.

Color Direction

The color system should feel soft and premium.
Use a restrained palette:

* one main accent color
* neutral surfaces
* subtle status colors
* readable contrast

Do not use too many bright colors.
Do not assign random colors to cards.

Motion Direction

Motion should be minimal and functional.
Use small hover, focus, and transition polish only.
Do not add decorative animations.

Responsive Direction

This product must be implemented with a mobile-first responsive approach.

Priority:

* mobile layout first
* tablet enhancement second
* desktop refinement last

The interface must remain usable on:

* mobile phones
* tablets
* laptop screens
* desktop screens

Dashboard grids must collapse cleanly.
Sidebar must convert into a mobile drawer.
Tables must stack or scroll as needed.
Forms must remain comfortable on small screens.

Non-Negotiable UI Rules

* use a modern bento-card layout
* design mobile first
* keep the interface clean and spacious
* use plain, professional English copy
* keep labels short and natural
* do not sound robotic
* do not sound gimmicky
* do not overuse badges, borders, or bright colors
* do not make the product feel like a generic admin template
* keep role experiences visually consistent but clearly separated by content
* use copy that supports action and clarity

Final UI Intent

The product should feel like a refined internal leadership tool:

* modern
* calm
* premium
* structured
* easy to scan
* easy to act on

The UI must support trust and clarity first.
It should look good, but it should never feel decorative without purpose.
