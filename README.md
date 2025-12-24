BeyondChats Technical Assignment
Overview

This repository contains a monorepo implementation of the BeyondChats technical assignment.
The project focuses on building a reliable backend content pipeline with scraping, REST APIs, and an LLM-ready enrichment architecture.

The implementation prioritizes:

Correctness and robustness

Clean separation of concerns

Idempotent data ingestion

Extensibility for AI/LLM use cases

Simple reviewer setup

Repository Structure

The project is organized as a monorepo to clearly separate responsibilities across services.

beyondchats-assignment/
│
├── backend-laravel/
│   ├── app/                  # Laravel application logic
│   ├── database/             # Migrations and SQLite database
│   ├── routes/               # API routes (/api/articles)
│   ├── app/Console/Commands/ # Artisan scraper command
│   ├── artisan               # Laravel CLI entry
│   └── README.md             # Backend-specific documentation
│
├── llm-node/
│   ├── index.js              # Express server entry point
│   ├── routes/               # API routes (e.g. /api/enrich)
│   ├── services/             # Mock LLM enrichment logic
│   ├── package.json          # Node.js dependencies
│   └── README.md             # LLM service documentation
│
├── frontend-react/
│   └── (planned)             # React frontend for article consumption
│
└── README.md                 # Root project documentation

High-Level Architecture

The system is designed as a modular content pipeline with clear separation between scraping, API delivery, and LLM enrichment.

Flow:

Blog articles are scraped from the BeyondChats website

Articles are stored in a local SQLite database

A REST API exposes the stored articles

A separate Node.js service is prepared for LLM-based enrichment

A frontend layer can consume the APIs without backend changes

This design ensures each layer can evolve independently.

Architecture Rationale

Scraping is isolated from API delivery

APIs are frontend-agnostic

LLM processing is decoupled from core backend logic

Each service can scale or change independently

Backend remains stable even when AI logic evolves

Phase 1 – Backend Scraper (Laravel)

The Laravel backend includes an Artisan command that scrapes blog articles from the BeyondChats website and stores them locally.

Features

Scrapes blog listing and individual article pages

Normalizes relative URLs to absolute URLs

Extracts article title and content

Stores the oldest articles

Idempotent ingestion (safe re-runs without duplication)

Graceful error handling

Command
php artisan scrape:beyondchats

Phase 2 – Articles API (Laravel)

A REST API exposes scraped articles for frontend or service consumption.

Endpoint
GET /api/articles

Features

Pagination support

Input validation

Consistent JSON responses

Deterministic ordering

Production-safe querying

Example
GET /api/articles?per_page=5

Phase 3 – LLM Enrichment Service (Node.js)

A standalone Node.js service demonstrates readiness for LLM-based article enrichment.

Purpose

Decouple AI/LLM logic from the core backend

Enable future enrichment (summaries, tags, sentiment, embeddings)

Avoid blocking or coupling the scraping pipeline

Current Scope

Express-based service skeleton

Health check endpoint

Mock enrichment logic

No external LLM APIs required

Endpoints
GET  /           # Health check
POST /api/enrich # Returns mocked enrichment data

Example Response
{
  "success": true,
  "data": {
    "summary": "Mock AI-generated summary",
    "tags": ["AI", "Chatbots", "Customer Support"],
    "sentiment": "neutral"
  }
}


This design allows easy replacement of mock logic with real LLM providers (OpenAI, Gemini, Claude, etc.) without modifying the Laravel backend.

Frontend (React)

A React frontend is planned to consume the /api/articles endpoint and display article data.

Frontend implementation is intentionally minimal, as the primary evaluation focus of this assignment is:

Backend scraping

API design

LLM integration readiness

Design Decisions

Monorepo structure for clear service separation

Microservice-style LLM architecture

Idempotent scraping for production safety

SQLite for portability and easy reviewer setup

LLM kept independent from core backend workflows

Status

✅ All core requirements of the BeyondChats technical assignment have been implemented.

The system is functional, extensible, and ready for further enhancement.

For detailed implementation notes, refer to the README files inside each service directory.
