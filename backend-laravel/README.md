BeyondChats Technical Assignment
Overview

This repository contains a monorepo implementation of the BeyondChats technical assignment.
The project focuses on building a reliable backend scraping pipeline to collect blog articles from BeyondChats, with an architecture designed for extensibility (LLM enrichment, frontend consumption).

The implementation emphasizes:

Clean architecture

Idempotent data ingestion

Real-world edge case handling

Production-safe design choices

Repository Structure
beyondchats-assignment/
│
├── backend-laravel/     # Laravel backend (scraping + APIs)
├── llm-node/            # Node.js service for future LLM enrichment
├── frontend-react/      # React frontend (article consumption)
└── README.md

Backend (Laravel)
Tech Stack

Laravel 10

PHP 8.1+

SQLite (for simplicity & portability)

Symfony HttpClient

Symfony DomCrawler

Why SQLite?

Zero configuration

Portable across environments

Ideal for assignments & demos

Easy reviewer setup

Scraper Design
Artisan Command

The scraper is implemented as a Laravel Artisan command:

php artisan scrape:beyondchats

What It Does

Fetches the BeyondChats blog listing page

Extracts blog article URLs

Normalizes relative URLs to absolute URLs

Selects the 5 oldest articles

Scrapes:

Title

Content

Source URL

Stores articles in the database

Key Engineering Decisions
1. Relative URL Normalization

Blog links are relative (e.g. /blogs/article-name).
These are normalized to absolute URLs using a base URL:

https://beyondchats.com


This avoids HTTP client errors and ensures consistent scraping.

2. Edge Case Handling

The blog listing page (/blogs/) itself is excluded to avoid parsing errors.

$link !== '/blogs/'


This prevents invalid content extraction and noisy logs.

3. Idempotent Scraping (Critical)

Before inserting an article, the scraper checks:

Article::where('source_url', $url)->exists()


This ensures:

No duplicate records

Safe re-runs

Cron-job compatibility

4. Production-Safe Design

Graceful skips instead of crashes

Clear console logs

Deterministic behavior on every run

Database Schema

articles table

id

title

slug

content

source_url (unique)

type

created_at

updated_at

How to Run (Reviewer Friendly)
Requirements

PHP 8.1+

Composer

Node.js (optional, for later phases)

Git

Setup Backend
cd backend-laravel
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate

Run Scraper
php artisan scrape:beyondchats


Re-running the command will safely skip already-scraped articles.

Assumptions & Tradeoffs
Assumptions

Blog HTML structure remains consistent

First <h1> represents article title

<article> tag contains main content

Tradeoffs

SQLite chosen over MySQL for simplicity

No pagination implemented (scope-limited to 5 articles)

No retry queue (out of assignment scope)

Future Improvements

Add REST APIs with pagination & filters

Schedule scraper via Laravel scheduler

Add Node.js LLM enrichment (summaries, tags)

Full React frontend for article browsing

Caching & rate limiting

Unit tests for scraper logic

Conclusion

This implementation prioritizes correctness, robustness, and clarity over unnecessary complexity.
The scraper is safe to run repeatedly, handles real-world edge cases, and is designed for extension into a full content pipeline.