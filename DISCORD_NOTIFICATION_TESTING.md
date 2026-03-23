Discord Notification Testing Guide

Purpose

This document explains how to test the daily Discord alert end‑to‑end using real data from the application, without changing product behaviour.

1. Test Environment Setup

- Create a dedicated Discord server/channel for testing.
- In that channel, create a webhook and copy its URL.
- In your `.env` file:
  - Set `DISCORD_NOTIFICATIONS_ENABLED=true`.
  - Set `DISCORD_WEBHOOK_URL=<your Discord webhook URL>`.
  - Optionally set `DISCORD_NOTIFICATION_TIMEOUT=5` (seconds) if needed.
- Reload configuration:
  - Run `php artisan config:clear`.

2. Prepare Rule‑Based Findings for a Reporting Day

Goal: make sure there is at least one qualifying flag for a specific reporting date.

- Choose a reporting date that already has real reporting data (for example `2026-03-20`).
- Ensure rule‑based flags have been evaluated for that date:
  - Run `php artisan flags:evaluate-day 2026-03-20`.
- This will create medium/high severity flags of type:
  - `missing_submission`
  - `late_submission`
  - `operational_dominance`
  - `repetitive_input`
  where the rule conditions are met.

3. Manual Discord Alert Send (Happy Path)

Use this to verify the end‑to‑end flow and the message format.

- Run:
  - `php artisan discord:send-daily-alert 2026-03-20`
- Expected results:
  - Exactly one new message appears in the test Discord channel with structure:
    - Header: `Daily Reporting Alert — 20 Mar 2026`.
    - Summary line: `X divisions • Y people • Z findings`.
    - Body grouped by division, each with `- Name – finding1, finding2` lines.
  - In PostgreSQL table `discord_notifications`:
    - There is one row with `reporting_date = 2026-03-20` and `status = sent`.
    - `attempt_count` is between 1 and 3.
    - `divisions_count`, `people_count`, and `findings_count` match the summary line.
    - `message` contains the exact text sent to Discord.

4. No‑Findings Case

Goal: confirm that no message is sent when there are no qualifying findings.

- Choose a date with no qualifying medium/high rule‑based flags (for example a day with no entries).
- Run:
  - `php artisan discord:send-daily-alert 2026-03-21`
- Expected results:
  - No new message appears in the Discord channel.
  - No new `discord_notifications` row is created for `2026-03-21`.
  - This verifies the "no-notification" rule for empty days.

5. Idempotency (No Duplicate Daily Alerts)

Goal: ensure the same reporting day cannot generate multiple alerts in normal operation.

- Reuse a date that already produced a successful alert, e.g. `2026-03-20`.
- Run the command again:
  - `php artisan discord:send-daily-alert 2026-03-20`
- Expected results:
  - No additional message is sent to Discord.
  - `discord_notifications` still has only one `status = sent` record for `2026-03-20`.
  - This confirms that the daily alert is idempotent.

6. Failure and Retry Behaviour (Optional)

Goal: confirm retry and failure logging without spamming real channels.

- Temporarily set an invalid webhook URL in `.env` (for example, change one character).
- Run:
  - `php artisan config:clear`
  - `php artisan discord:send-daily-alert 2026-03-22`
- Expected results:
  - No message appears in the Discord channel.
  - In `discord_notifications`:
    - A record for `reporting_date = 2026-03-22` with `status = failed`.
    - `attempt_count = 3` (one original attempt + two retries).
    - `error_message` contains the HTTP error or exception message (truncated).

7. Scheduler Integration Check

Goal: verify that the scheduled job is wired correctly.

- The scheduler is configured in `routes/console.php` to run:
  - `discord:send-daily-alert` daily at `19:00` application time.
- To test locally without setting up cron, you can run:
  - `php artisan schedule:run`
- If run after 19:00 on a day with qualifying findings, you should see:
  - The daily alert being processed.
  - A new `discord_notifications` entry and Discord message as described above.

Notes

- Discord is a delivery channel only. The source of truth for findings remains the application data and notification logs.
- Testing should always use a non-production Discord channel to avoid confusing real stakeholders.

