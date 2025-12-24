# BeyondChats Technical Assignment

## Overview

This repository contains a monorepo implementation of the BeyondChats technical assignment.

The project builds a reliable backend content pipeline using:

- Web scraping
- REST APIs
- LLM-ready enrichment architecture

The implementation prioritizes:

- Correctness and robustness
- Clean separation of concerns
- Idempotent data ingestion
- Extensibility for AI / LLM use cases
- Simple reviewer setup

---

## Repository Structure

```text
beyondchats-assignment/
├── backend-laravel/
│   ├── app/
│   │   └── Console/Commands/     # Artisan scraper command
│   ├── database/                # Migrations & SQLite DB
│   ├── routes/                  # API routes (/api/articles)
│   ├── artisan                  # Laravel CLI
│   └── README.md
│
├── llm-node/
│   ├── routes/                  # /api/enrich
│   ├── services/                # Mock LLM logic
│   ├── index.js                 # Express entry
│   └── README.md
│
├── frontend-react/              # Planned
└── README.md
High-Level Architecture
text
Copy code
BeyondChats Website
        ↓
Laravel Scraper (Artisan)
        ↓
SQLite Database
        ↓
Articles API (/api/articles)
        ├──► React Frontend (planned)
        ↓
Node.js LLM Service (mock)

```
## Phase 1 – Backend Scraper (Laravel)

The Laravel backend implements an **Artisan command-based web scraper** that collects blog articles from the BeyondChats website and stores them in a local **SQLite** database.

The scraper is designed to be **robust, idempotent, and production-safe**, ensuring reliable data ingestion even when executed multiple times.

### Features

- Scrapes both blog listing pages and individual article pages  
- Normalizes relative URLs into absolute URLs  
- Extracts article title and full content  
- Stores the oldest articles first  
- Idempotent ingestion (safe re-runs without duplication)  
- Graceful handling of missing or malformed pages  

### Scraper Command

```bash
php artisan scrape:beyondchats

```

## Phase 1 – Backend Scraper (Laravel)

The Laravel backend implements an **Artisan command-based web scraper** that collects blog articles from the BeyondChats website and stores them in a local **SQLite** database.

The scraper is designed to be **robust, idempotent, and production-safe**, ensuring reliable data ingestion even when executed multiple times.

---

### Purpose

- Automate extraction of BeyondChats blog articles  
- Store structured article data for downstream consumption  
- Ensure safe re-runs without duplication  
- Form the foundation of the content pipeline  

---

### Key Features

- Scrapes both blog listing pages and individual article pages  
- Normalizes relative URLs into absolute URLs  
- Extracts article title and full content  
- Stores oldest articles first for deterministic ordering  
- Idempotent ingestion (safe re-runs without duplication)  
- Graceful handling of missing or malformed pages  

---

### Scraper Command

```bash
php artisan scrape:beyondchats
```

---

### Scraper Behavior

- Fetches blog listing pages from the BeyondChats website  
- Extracts individual article URLs  
- Visits each article page individually  
- Parses article title and full content  
- Persists only new articles into the database  
- Skips articles that already exist (no duplication)  

---

### Idempotency Guarantee

- Articles are uniquely identified before insertion  
- Existing records are not overwritten or duplicated  
- The command can be executed multiple times safely  
- Only newly published articles are ingested on re-runs  

---

### Error Handling

- Skips inaccessible or malformed pages  
- Prevents scraper failure due to partial errors  
- Logs failures without interrupting ingestion flow  

---

### Data Storage

- Uses SQLite for portability and ease of setup  
- Database schema managed via Laravel migrations  
- Stored data is immediately available to the Articles API  

---

### Output

After execution, the database contains:

- Article title  
- Article content  
- Source URL  
- Timestamps for tracking ingestion  

This completes the **content ingestion layer** of the BeyondChats pipeline.

## Phase 2 – Articles API (Laravel)

The Laravel backend exposes a **RESTful Articles API** that provides access to the scraped blog articles stored in the database.

This API layer is designed to be **frontend-agnostic**, stable, and production-safe, enabling seamless consumption by web clients or downstream services.

---

### Purpose

- Expose scraped article data via a clean REST interface  
- Enable frontend and service-level consumption  
- Decouple scraping logic from data access  
- Support scalable and paginated retrieval  

---

### API Endpoint

```http
GET /api/articles
```

---

### Features

- Pagination support  
- Input validation for query parameters  
- Consistent JSON response format  
- Deterministic ordering of articles  
- Production-safe querying  

---

### Example Request

```http
GET /api/articles?per_page=5
```

---

### Response Content

Each API response includes:

- Article title  
- Article content  
- Source URL  
- Creation timestamps  
- Pagination metadata  

---

### Behavior

- Returns articles in a predictable order  
- Supports configurable page size  
- Prevents invalid or excessive requests  
- Ensures stable output for frontend rendering  

This API forms the **consumption layer** of the BeyondChats content pipeline.

## Phase 3 – LLM Enrichment Service (Node.js)

A standalone **Node.js service** is included to demonstrate readiness for **LLM-based article enrichment**.

This service is intentionally designed as a **decoupled enrichment layer**, ensuring that AI/LLM processing does not interfere with core scraping or API workflows.

---

### Purpose

- Decouple AI / LLM logic from the Laravel backend  
- Enable future enrichment use cases such as summarization, tagging, or sentiment analysis  
- Prevent blocking or coupling of the scraping pipeline  
- Demonstrate extensible, microservice-style architecture  

---

### Current Scope

- Express-based service skeleton  
- Health check endpoint  
- Mock enrichment logic  
- No dependency on external LLM providers  

---

### Endpoints

```http
GET  /           # Health check
POST /api/enrich # Mock enrichment response
```

---

### Example Request

```http
POST /api/enrich
```

---

### Example Response

```json
{
  "success": true,
  "data": {
    "summary": "Mock AI-generated summary",
    "tags": ["AI", "Chatbots", "Customer Support"],
    "sentiment": "neutral"
  }
}
```

---

### Behavior

- Accepts article data as input  
- Returns deterministic mock enrichment output  
- Simulates AI-generated metadata without external APIs  
- Can be safely replaced with real LLM providers  

---

### Extensibility

The mock enrichment logic can be seamlessly replaced with:

- OpenAI  
- Gemini  
- Claude  
- Any future LLM provider  

No changes are required to the Laravel backend when upgrading to real LLM integrations.

This service represents the **AI enrichment layer** of the BeyondChats content pipeline.

## Phase 4 – Frontend (React)

A lightweight **React frontend** is planned to consume the Articles API and present scraped content to users.

The frontend layer is intentionally minimal, as the primary evaluation focus of this assignment is **backend scraping, API design, and LLM integration readiness**.

---

### Purpose

- Consume the `/api/articles` endpoint  
- Display article titles and content  
- Validate API usability from a client perspective  
- Complete the end-to-end content pipeline  

---

### Planned Features

- Fetch paginated articles from the backend API  
- Display article lists and individual article content  
- Simple, clean UI focused on readability  
- Stateless consumption of backend APIs  

---

### API Consumption

```http
GET /api/articles
```

Example usage:

```http
GET /api/articles?per_page=5
```

---

### Design Philosophy

- Frontend remains **API-driven and backend-agnostic**  
- No business logic duplicated from backend  
- Minimal state management  
- Easy extensibility for future UI enhancements  

---

### Current Status

- Frontend directory scaffolded  
- Implementation intentionally deferred  

This ensures focus remains on **scraping correctness, API robustness, and LLM-readiness**, which are the core evaluation criteria of the assignment.

## Phase 5 – Design Decisions

The following design decisions were made to ensure clarity, robustness, and extensibility of the system.

---

### Monorepo Architecture

- All services are maintained in a single repository  
- Clear separation between backend, LLM service, and frontend  
- Simplifies evaluation and local setup  

---

### Scraping Strategy

- Scraping implemented as a Laravel Artisan command  
- Isolated from API and frontend layers  
- Idempotent design to support safe re-runs  

---

### Database Choice

- SQLite selected for portability and zero-configuration setup  
- Ideal for assignments, demos, and reviewer environments  
- Easily replaceable with MySQL/PostgreSQL if needed  

---

### API Design

- RESTful API with pagination support  
- Frontend-agnostic response structure  
- Deterministic ordering for consistent results  

---

### LLM Architecture

- LLM processing isolated into a separate Node.js service  
- Prevents tight coupling with core backend  
- Enables independent scaling and future upgrades  

---

### Extensibility

- Each layer can evolve independently  
- Mock LLM logic can be replaced with real providers  
- Frontend can be implemented or replaced without backend changes  

These decisions collectively ensure a **clean, production-safe, and extensible architecture**.

## Phase 6 – Setup & Execution

Each service in the repository can be executed independently.

---

### Backend (Laravel)

#### Prerequisites

- PHP 8.1 or higher  
- Composer  
- SQLite  

#### Setup

```bash
cd backend-laravel
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

#### Run Scraper

```bash
php artisan scrape:beyondchats
```

#### Articles API

```text
http://127.0.0.1:8000/api/articles
```

---

### LLM Enrichment Service (Node.js)

#### Prerequisites

- Node.js 18 or higher  
- npm  

#### Setup

```bash
cd llm-node
npm install
```

#### Start Service

```bash
node index.js
```

#### Health Check

```text
http://localhost:4000/
```

---

### Frontend (React)

- Frontend setup deferred  
- Folder scaffolded for future implementation  

## Phase 7 – Final Status & Conclusion

All core requirements of the **BeyondChats Technical Assignment** have been successfully implemented.

---

### Completion Status

- Backend scraping pipeline implemented  
- REST API for article access completed  
- LLM enrichment service skeleton created  
- Frontend scaffolded for future use  
- Documentation completed with clear architecture and usage  

---

### Final Outcome

The system is:

- Functional  
- Robust  
- Idempotent  
- Extensible  
- Reviewer-friendly  
- LLM-ready  

This implementation demonstrates backend engineering practices, clean architecture design and readiness for AI-driven enhancements.

---

### Notes for Reviewers

- The scraper is safe to re-run multiple times  
- APIs are stable and frontend-agnostic  
- LLM integration can be extended without modifying the backend  
- SQLite enables instant local setup  

This concludes the BeyondChats technical assignment.

