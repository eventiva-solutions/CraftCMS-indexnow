# IndexNow for Craft CMS

**Instantly notify search engines whenever your content changes.** IndexNow is an open protocol that lets you push URL change notifications directly to search engines — no more waiting days for crawlers to discover your updates.

---

## Why IndexNow?

Search engines traditionally discover content changes by periodically crawling your site. With IndexNow, you flip that model: as soon as an entry is published, updated, or deleted in Craft, search engines are notified immediately. This means faster indexing, more accurate search results, and less unnecessary crawl traffic on your server.

IndexNow is supported by **Bing**, **Yandex**, **Seznam**, and the shared **IndexNow API** — one submission reaches them all.

---

## Features

### Automatic Submission
The plugin listens for Craft entry save and delete events. Whenever a live entry with a public URL is changed, its URL is automatically queued and submitted — no manual action required.

### Two Submission Modes
- **Immediate:** URLs are sent to the search engine the moment an entry is saved. Ideal for sites with real-time content requirements.
- **Queued:** Changed URLs accumulate in a queue and are submitted in a single batch via a scheduled cron job. Ideal for high-volume sites or when you want to minimize API calls.

### Manual Actions
The control panel gives you full manual control at any time:
- Submit the current pending queue
- Submit every live page on your site in one go
- Clear the queue or the submission log

### API Key Management
Generate a cryptographically secure API key directly from the control panel. The plugin automatically creates the required verification file (e.g. `abc123.txt`) in your web root — no FTP, no manual file management.

### Submission Log
Every submission is recorded with timestamp, HTTP status code, URL count, and any error details. The log holds the last 50 entries and is visible directly in the control panel.

### Console Commands
Automate submissions from your server's cron scheduler:

```bash
# Submit pending URLs
php craft indexnow/submit

# Submit all live pages
php craft indexnow/submit/all
```

### Multi-Engine Support
Choose your preferred endpoint:
- `api.indexnow.org` (recommended — reaches all participating engines)
- `www.bing.com`
- `yandex.com`
- `search.seznam.cz`

### Fully Translated
Ships with English and German translations out of the box. All control panel strings use Craft's translation system, so adding further languages is straightforward.

---

## Requirements

- **Craft CMS** 5.7.0 or later
- **PHP** 8.2 or later
- cURL extension enabled

---

## Setup

1. Install the plugin via the Craft Plugin Store or Composer.
2. Go to **IndexNow** in the Craft control panel.
3. Click **Generate new key** — the plugin creates the API key and the required verification file in your web root automatically.
4. Enable the plugin and choose your submission mode (immediate or queued).
5. If using queued mode, add the cron command shown in the settings to your server's crontab.

That's it. Every content change from this point on will be reported to search engines automatically.

---

## Cron Example

```bash
# Submit pending IndexNow URLs every day at 3:00 AM
0 3 * * * php /var/www/html/craft indexnow/submit
```

---

## Supported Search Engines

| Engine | Supported via IndexNow |
|---|---|
| Bing / Microsoft | ✅ |
| Yandex | ✅ |
| Seznam | ✅ |
| Google | Participation announced, check google.com/indexnow for current status |

---

## License

This plugin requires a paid license. Licenses are managed through the [Craft Plugin Store](https://plugins.craftcms.com).
