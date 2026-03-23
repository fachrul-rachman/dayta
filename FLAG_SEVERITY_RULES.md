Flag Severity Rules

Core Principle

Flag severity must be rule-based, explainable, reproducible, and non-subjective.
Severity must reflect business impact on reporting discipline, visibility, and leadership monitoring value.
Do not assign severity based on vague judgment.
Do not change severity dynamically without an explicit rule.

Allowed Severity Levels

Only these severity levels are allowed:

* low
* medium
* high

Severity Meaning

Low
Used for minor warning signals that are still useful for local monitoring, but do not require leadership alerting.

Medium
Used for meaningful reporting discipline or execution signals that deserve management attention and are eligible for daily Discord notification.

High
Used for serious visibility or reporting failures that significantly reduce leadership visibility or indicate strong operational concern. High severity is eligible for daily Discord notification and should be prioritized in dashboards and summaries.

Severity Decision Model

Severity should be determined from business impact, not personal judgment.

Use these principles:

1. Visibility Loss
   How much does this issue reduce the ability of HoD or Director to understand what happened today?

2. Reporting Discipline Impact
   How much does this issue indicate poor reporting discipline or weak reporting reliability?

3. Strategic Signal Quality
   How much does this issue reduce trust in the reporting signal for management monitoring?

4. Leadership Relevance
   Would this issue reasonably deserve leadership attention in today’s monitoring context?

Default Severity Mapping for MVP

The system must use the following severity mapping for the current MVP scope.

1. missing_submission
   Severity: high

Definition
The user has no valid required submission for the reporting date after the relevant cutoff.

Business Reason
This is a major visibility failure.
Leadership cannot reliably read that person’s reporting condition for the day.
This is the strongest reporting failure among the current core flags.

Notification Rule
Eligible for daily Discord notification.

2. late_submission
   Severity: medium

Definition
The user submitted after the allowed reporting cutoff but still submitted within a detectable late window according to the report settings and workflow rules.

Business Reason
The data still exists, but reporting discipline is weak.
This is important enough for management attention, but not as severe as a total missing submission.

Notification Rule
Eligible for daily Discord notification.

3. operational_dominance
   Severity: medium

Definition
The user’s reporting for the evaluated period is dominated by operational or ad hoc work beyond the allowed or expected threshold, resulting in weak alignment to Big Rock or strategic work.

Business Reason
This is not a missing report, but it signals a meaningful business concern.
It suggests that execution may be drifting away from the intended priorities.

Notification Rule
Eligible for daily Discord notification.

4. repetitive_input
   Severity: medium only when strict repetition criteria are met

Definition
A reporting entry is considered repetitive only when the content is highly similar to the same user’s recent reporting history based on the configured repetition rule.

Business Reason
Repetitive reporting is treated as a reporting-discipline issue, not a minor cosmetic issue.
If it passes the defined threshold, it suggests copy-paste behavior or low-quality reporting input that reduces trust in the signal.
Because leadership considers this meaningful, it should be treated as medium when the rule is clearly met.

Notification Rule
Eligible for daily Discord notification only when the rule is truly met.

Repetitive Input Rule

Core Rule

Do not mark repetitive_input based on loose judgment.
This flag must only be created when repetition is strong enough to indicate low-quality or copy-paste style reporting.

Required Logic Direction

The repetition check must compare the current user’s reporting content against that same user’s recent historical reporting content only.
Do not compare one user against other users.
Do not compare across divisions.

Minimum Rule Requirements

A repetitive_input flag may only be created when all of these conditions are true:

* the comparison is against the same user’s recent reporting history
* the compared content is highly similar or effectively identical
* similarity passes the defined threshold
* the repetition is strong enough to be explainable as a real discipline issue, not a coincidence
* the flag result can be reproduced consistently from the same data

Recommended MVP Rule

For the first implementation, use a strict conservative rule.

Recommended rule:

* evaluate the current day’s plan and/or realization text against that same user’s recent entries
* only create repetitive_input when there is near-identical repetition across the configured comparison window
* avoid aggressive fuzzy logic in the first version
* prefer false negative over false positive

Recommended conservative direction:

* use normalized text comparison
* ignore casing and trivial spacing differences
* optionally ignore punctuation-only differences
* do not flag based on short generic phrases alone
* do not flag if the repeated text is too short to be meaningful
* do not flag if only one small item happens to repeat naturally
* prioritize repeated full-item or repeated multi-item pattern detection

Examples of Repetitive Input That May Qualify

May qualify:

* the same user submits nearly identical plan items across multiple recent days with minimal meaningful variation
* the same user repeatedly submits the same realization text in a way that clearly looks copied rather than updated
* most or all items in the current submission match recent prior submissions too closely

Should not qualify:

* normal recurring work with reasonable wording differences
* one repeated short phrase such as “follow up”
* repeated references to the same Big Rock with different real work descriptions
* operationally similar work described with meaningful variation

Recommended Threshold Philosophy

Use a strict threshold.
Do not treat mild similarity as repetitive_input.
Only flag when the repetition is strong enough that a reviewer could reasonably agree the reporting quality is poor.

If the threshold is uncertain, choose the stricter interpretation and do not flag.

Flag Creation Rules

General Rules

* flags must be generated from explicit logic
* do not assign severity manually in the UI
* do not let users override their own severity
* do not invent additional severities
* do not downgrade or upgrade the defined MVP severities unless this document is updated

Per-Day Scope Rule

Flags used for daily Discord notification must be based on the reporting date being evaluated.
Do not carry old flags into a new day’s Discord message unless the current day itself generates a new qualifying flag.

Daily Discord Eligibility Rule

Only flags with severity:

* medium
* high

are eligible for daily Discord notification.

Low severity flags must not trigger Discord.

Current MVP result:

* missing_submission -> eligible
* late_submission -> eligible
* operational_dominance -> eligible
* repetitive_input -> eligible only when the strict repetition rule is met

Sorting / Priority Rule

When multiple flags are shown together:

* high severity must appear before medium
* medium severity must appear before low
* within the same severity, use the most relevant business ordering available

Display Guidance

Dashboard and Summary Use

* high severity must be visually prioritized
* medium severity must remain clearly visible
* low severity must remain readable but less dominant

Discord Use

* only medium and high appear
* group findings by division
* then by person
* then list the finding titles clearly
* only include findings for the reporting day being evaluated
* send at most one daily Discord message for that day

Explainability Requirement

Every flag must remain explainable.
For each flag type, the system should be able to answer:

* why was this flag created
* what rule was matched
* what severity was assigned
* why that severity is correct under this document

Do Not Do These

* do not assign severity subjectively
* do not use AI to decide severity
* do not mark repetitive_input as medium from loose similarity
* do not compare repetitive_input across different users
* do not notify Discord for low severity
* do not carry over old findings into today’s Discord message unless today’s data generates them again
* do not invent more flag types or severity mappings outside this document without explicit instruction

Final MVP Severity Mapping

Use this mapping exactly for the first production version:

* missing_submission = high
* late_submission = medium
* operational_dominance = medium
* repetitive_input = medium only when strict repetition criteria are met

If severity logic needs to expand later, extend this document explicitly rather than improvising in code.

Kalau mau, setelah ini saya bisa lanjut buatin versi **prompt singkat ke Codex** untuk file ini, jadi Anda tinggal suruh dia implement sesuai MD ini.
