<div align="center">

<img src="public/images/dot_engage.png" alt="Dot.Engage" width="200" />

<h1>Dot.Engage</h1>

<p>Client engagement platform — contracts, real-time chat, and video calls in one place.</p>

[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-4E56A6?style=flat-square)](https://livewire.laravel.com)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?style=flat-square&logo=postgresql&logoColor=white)](https://postgresql.org)
[![Tests](https://img.shields.io/badge/tests-12%20passing-brightgreen?style=flat-square)](tests/)
[![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)](LICENSE)

</div>

---

## Overview

Dot.Engage is the client engagement platform in the Dot ecosystem. Businesses manage the full client lifecycle — from signed contracts and secure document exchange to real-time messaging and video consultations — in a single, unified workspace.

---

## Features

- **Contracts** — digitally signed PDF contracts generated with DomPDF, with audit trail
- **Real-time Chat** — Livewire 3 chat rooms powered by Laravel Reverb (Echo WebSockets)
- **Video Calls** — Daily.co embedded video frame for in-platform consultations
- **Client Portal** — branded workspace for each client with activity timeline
- **Secure File Exchange** — attach documents to contracts and messages
- **Notifications** — real-time alerts for contract signatures, messages, and meeting reminders
- **Ecosystem SSO** — authenticate from InfoDot with a single click

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 + PHP 8.4 |
| Frontend | Livewire 3 + Alpine.js + Tailwind CSS |
| Auth | Jetstream 5 + Sanctum (ecosystem SSO) |
| Database | PostgreSQL 16 (shared infodot instance) |
| WebSockets | Laravel Reverb + laravel-echo |
| PDF | DomPDF |
| Video | Daily.co |

---

## Quick Start

```bash
git clone https://github.com/sakhileb/Dot.Engage.git && cd Dot.Engage
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate && npm run dev & php artisan serve
php artisan reverb:start   # Real-time chat
```

```bash
bash bin/test.sh   # 12 tests passing
```

---

## Part of the Dot Ecosystem

Dot.Engage connects to [InfoDot](https://github.com/sakhileb/InfoDot) — the central hub. Log in to InfoDot once and navigate here without re-authenticating via `/auth/ecosystem`.

---

MIT — © SK Digital / BluPin Incorporated
