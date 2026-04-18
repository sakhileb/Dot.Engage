<p align="center">
  <img src="public/images/dot_engage.png" alt="Dot.Engage Logo" width="220" />
</p>

<h1 align="center">Dot.Engage</h1>

<p align="center">
  A business contract-sharing, real-time chat, and video-call document-signing platform built with Laravel 13.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 13" />
  <img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.3+" />
  <img src="https://img.shields.io/badge/Livewire-3-FB70A9?style=flat-square&logo=livewire&logoColor=white" alt="Livewire 3" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-3-38BDF8?style=flat-square&logo=tailwind-css&logoColor=white" alt="Tailwind CSS 3" />
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="MIT License" />
</p>

---

## Overview

**Dot.Engage** is a production-ready, multi-tenant SaaS platform that brings together document contract management, real-time team chat, and live video conferencing with embedded e-signature capabilities — all under one roof.

Teams can upload and version-control legal/business contracts, collect legally-auditable canvas-based e-signatures (IP and user-agent logged), collaborate in real-time chat, launch live video sessions with documents loaded for in-call signing, and automatically receive signed contract PDFs by email.

---

## Features

### Authentication & Multi-Team Management
- User registration, login, email verification, and password reset
- Two-factor authentication (2FA) via Laravel Jetstream
- Profile photo uploads
- **Multi-team support** — users can belong to multiple teams with all data team-scoped
- Team invitations and membership management with roles (`admin` / `member`)
- API token management via Laravel Sanctum

### Contract Management
- **Multi-step upload wizard** — metadata, file upload, and confirmation steps
- PDF and document inline preview
- **Contract lifecycle** — `draft` → `pending` → `signed` / `rejected`
- Contract expiry dates and soft deletion
- **Version history** — every re-upload or signed copy creates a new auditable version
- **Share contracts** with team members via modal, triggering email and database notifications
- **Canvas e-signatures** captured via the `signature_pad` library, with IP address and user-agent audit trail recorded per signature
- Signed contracts rendered immutable — no edits or deletions after signing
- **Secure PDF streaming** — files never exposed publicly; served only through an authenticated API endpoint
- Automatic PDF generation of signed contracts and email delivery to all parties

### Real-time Chat
- **1-on-1 direct messages** and **group conversations**
- Paginated message thread with read/unread tracking per participant
- **File attachments** in messages (stored on a private disk)
- **Contract sharing in chat** — link a contract directly in a message thread
- Unread message badge with real-time count updates
- All conversations scoped to the current team

### Video Sessions
- Create a video room with an optional attached contract
- Live video calling powered by the **Daily.co WebRTC SDK**
- **In-call document viewer** — review contracts without leaving the session
- **In-call signature pad** — capture e-signatures during a live call
- Presence channel shows a live list of session participants
- Signatures captured in-call are automatically promoted to auditable `ContractSignature` records when the session ends
- Stale session cleanup via scheduled Artisan command

### Real-time Notifications
- **Notification bell** with live unread count in the navigation bar
- Dropdown tray with recent notifications and mark-as-read
- Notifications delivered via both **email** and **database** channels for:
  - New chat message received
  - Contract shared with you
  - Contract signed (individual or all parties)
  - Signature requested during a live call
  - Video session invitation

### Dashboard
Team-scoped overview showing:
- Total and pending contract counts
- 5 most recent contracts
- 5 most recent conversations
- Up to 5 active video sessions

---

## Technology Stack

### Backend
| Package | Version | Purpose |
|---|---|---|
| `laravel/framework` | ^13.0 | Core framework |
| `laravel/jetstream` | ^5.5 | Authentication scaffold + Teams |
| `laravel/reverb` | ^1.10 | Self-hosted WebSocket server |
| `laravel/sanctum` | ^4.0 | API token authentication |
| `livewire/livewire` | ^3.6 | Reactive server-driven UI |
| `barryvdh/laravel-dompdf` | ^3.1 | Signed contract PDF generation |
| `spatie/laravel-medialibrary` | ^11.21 | File and media management |

### Frontend
| Package | Version | Purpose |
|---|---|---|
| `tailwindcss` | ^3.4 | Utility-first CSS framework |
| `@tailwindcss/forms` | ^0.5 | Form style resets |
| `@tailwindcss/typography` | ^0.5 | Prose typography |
| `@daily-co/daily-js` | ^0.89 | WebRTC video calling SDK |
| `signature_pad` | ^5.1 | Canvas-based e-signature capture |
| `vite` | ^8.0 | Frontend build tool |

---

## Architecture

### Broadcasting (Laravel Reverb)
Self-hosted WebSocket server broadcasting across four channel types:

| Channel | Type | Purpose |
|---|---|---|
| `App.Models.User.{id}` | Private | Personal notifications per user |
| `team.{teamId}` | Private | Team-wide contract and session events |
| `conversation.{conversationId}` | Private | Real-time message delivery |
| `video-session.{sessionId}` | Presence | Live participant list for video rooms |

### Broadcast Events
| Event | Channel | Payload |
|---|---|---|
| `MessageSent` | `conversation.{id}` | Message body, type, timestamps |
| `ContractShared` | `team.{id}` | Contract ID and title |
| `ContractSigned` | `team.{id}` | Contract ID, title, status, signer |
| `VideoSessionStarted` | `team.{id}` | Session ID, room UUID, contract ID |
| `VideoSessionEnded` | `team.{id}` | Session ID, timestamps |
| `SignatureRequestedDuringCall` | `team.{id}` | Session ID, contract details, signer name |

### Background Jobs
| Job | Queue | Purpose |
|---|---|---|
| `ProcessContractUpload` | database (3 tries) | Validate upload; advance status `draft` → `pending` |
| `GenerateSignedContractPdf` | database (3 tries) | Create signed PDF version; chain email dispatch |
| `DispatchSignedContractEmail` | database (5 tries) | Send `SignedContractMail` to all signers and creator |
| `ArchiveVideoSession` | database (3 tries) | Mark session ended; promote in-call signatures |

### Artisan Commands
| Command | Purpose |
|---|---|
| `dotengage:clean-expired-sessions` | Mark stale `waiting`/`active` sessions as `ended`; supports `--dry-run` and `--hours` options |
| `dotengage:team-activity-report` | Generate monthly CSV/table report of contracts, messages, and video sessions per team |

---

## Database Schema

| Table | Purpose |
|---|---|
| `users` | Jetstream base with 2FA columns |
| `teams`, `team_user`, `team_invitations` | Jetstream Teams multi-tenancy |
| `personal_access_tokens` | Sanctum API tokens |
| `contracts` | Contract records with status, expiry, soft deletes |
| `contract_signatures` | Per-signature IP address + user-agent audit trail |
| `contract_versions` | Full version history per contract |
| `conversations` | 1:1 DMs and group conversations, soft deletes |
| `conversation_participants` | Pivot with `last_read_at` tracking |
| `messages` | Text, file, and contract message types, soft deletes |
| `message_attachments` | File metadata for chat attachments |
| `video_sessions` | UUID room ID, status lifecycle, optional contract link |
| `video_session_signatures` | In-call captured signatures |
| `notifications` | Laravel database notifications |
| `media` | Spatie Media Library |
| `jobs`, `cache`, `sessions` | Laravel infrastructure |

### Private Storage Disks
| Disk | Path | Purpose |
|---|---|---|
| `contracts` | `storage/app/private/contracts/` | Uploaded and versioned PDFs |
| `signatures` | `storage/app/private/signatures/` | Exported signature PNG images |
| `attachments` | `storage/app/private/attachments/` | Chat message file attachments |

---

## Installation

### Requirements
- PHP 8.3+
- Composer
- Node.js 20+
- SQLite (zero-config) or MySQL/PostgreSQL

### Quick Start

```bash
# 1. Clone the repository
git clone https://github.com/sakhileb/Dot.Engage.git
cd Dot.Engage

# 2. Install all dependencies and scaffold the environment
composer setup
# This installs PHP and JS dependencies, copies .env, generates the app key,
# runs migrations, and builds frontend assets.

# 3. Start all development processes (server, queue worker, log viewer, Vite)
composer dev
```

### Manual Setup

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy and configure environment
cp .env.example .env
php artisan key:generate

# Create SQLite database and run migrations
touch database/database.sqlite
php artisan migrate

# Link public storage for profile photos
php artisan storage:link

# Build frontend assets
npm run build
```

### Running Services

Run each in a separate terminal:

```bash
# Laravel development server
php artisan serve

# Queue worker (required for jobs and notifications)
php artisan queue:listen

# Reverb WebSocket server (required for real-time features)
php artisan reverb:start

# Vite dev server (hot module replacement)
npm run dev
```

---

## Environment Configuration

Key variables to configure in `.env`:

```env
# Application
APP_NAME="Dot.Engage"
APP_URL=http://localhost

# Database (SQLite default — zero config)
DB_CONNECTION=sqlite

# Queue, session, and cache (all database-backed)
QUEUE_CONNECTION=database
SESSION_DRIVER=database
CACHE_STORE=database

# Broadcasting — Laravel Reverb
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

# Mail (use "log" for local development)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@dotengage.app"
MAIL_FROM_NAME="Dot.Engage"

# Expose Reverb config to Vite frontend
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

---

## Testing

```bash
# Run the full test suite
php artisan test

# With coverage report
php artisan test --coverage
```

---

## License

This project is open-sourced software licensed under the [MIT License](LICENSE).
