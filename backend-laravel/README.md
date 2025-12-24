# BeyondChats Backend – Laravel Scraper & API

## Overview

This directory contains the **Laravel backend implementation** for the BeyondChats technical assignment.

The backend focuses on building a **reliable, production-safe scraping pipeline** to collect blog articles from the BeyondChats website and store them in a structured database, with future extensibility for APIs, LLM enrichment, and frontend consumption.

The implementation emphasizes:

- Clean architecture  
- Idempotent data ingestion  
- Real-world edge case handling  
- Production-safe design choices  

---

## Tech Stack

- Laravel 10  
- PHP 8.1+  
- SQLite (for simplicity and portability)  
- Symfony HttpClient  
- Symfony DomCrawler  

---

## Why SQLite?

SQLite is used intentionally for this assignment because:

- Zero configuration required  
- Portable across environments  
- Ideal for demos and assignments  
- Enables fast and easy reviewer setup  

---

## Scraper Design

### Artisan Command

The scraper is implemented as a **Laravel Artisan command**:

```bash
php artisan scrape:beyondchats
```

---

### What the Scraper Does

- Fetches the BeyondChats blog listing page  
- Extracts individual blog article URLs  
- Normalizes relative URLs into absolute URLs  
- Selects the **oldest articles**  
- Scrapes the following fields:
  - Title  
  - Content  
  - Source URL  
- Stores articles in the database  

---

## Key Engineering Decisions

### 1. Relative URL Normalization

Blog links are provided as relative paths (e.g. `/blogs/article-name`).

These are normalized into absolute URLs using a base URL:

```text
https://beyondchats.com
```

This avoids HTTP client errors and ensures consistent scraping behavior.

---

### 2. Edge Case Handling

The blog listing page itself (`/blogs/`) is explicitly excluded to avoid parsing errors.

```php
$link !== '/blogs/'
```

This prevents invalid content extraction and noisy logs.

---

### 3. Idempotent Scraping (Critical)

Before inserting an article, the scraper checks whether it already exists:

```php
Article::where('source_url', $url)->exists()
```

This guarantees:

- No duplicate records  
- Safe re-runs of the scraper  
- Cron-job compatibility  

---

### 4. Production-Safe Design

- Graceful skips instead of crashes  
- Clear console logging  
- Deterministic behavior on every run  

---

## Database Schema

The scraper stores data in the `articles` table with the following fields:

- `id`  
- `title`  
- `slug`  
- `content`  
- `source_url` (unique)  
- `type`  
- `created_at`  
- `updated_at`  

---

## How to Run (Reviewer Friendly)

### Requirements

- PHP 8.1+  
- Composer  
- Git  
- Node.js (optional, for later phases)  

---

### Setup Backend

```bash
cd backend-laravel
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

---

### Run Scraper

```bash
php artisan scrape:beyondchats
```

Re-running the command will safely skip already-scraped articles.

---

## Assumptions & Tradeoffs

### Assumptions

- Blog HTML structure remains consistent  
- First `<h1>` represents the article title  
- `<article>` tag contains the main content  

---

### Tradeoffs

- SQLite chosen over MySQL for simplicity  
- Pagination limited to oldest articles (scope-limited)  
- Retry queues excluded (out of assignment scope)  

---

## Future Improvements

- Add REST APIs with pagination and filters  
- Schedule scraper via Laravel Scheduler  
- Integrate Node.js LLM enrichment (summaries, tags)  
- Implement full React frontend for article browsing  
- Add caching and rate limiting  
- Write unit tests for scraper logic  

---

## Conclusion

This backend implementation prioritizes **correctness, robustness, and clarity** over unnecessary complexity.

The scraper is safe to run repeatedly, handles real-world edge cases, and is designed to scale into a full content pipeline with APIs, LLM enrichment, and frontend consumption.
