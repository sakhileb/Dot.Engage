 Dot.Engage — Platform Build Tasklist
> Laravel Jetstream (Teams) · Livewire · Tailwind CSS · SQLite
> A business contract-sharing, real-time chat, and video-call document-signing platform.

---

## Legend
- [ ] = Pending
- [x] = Complete
- `inline code` = exact artisan / shell command to run

---

## Phase 0 — Environment Bootstrap

- [x] Create the project
  ```bash
  composer create-project laravel/laravel dot-engage
  cd dot-engage
  ```

- [x] Install Jetstream with Teams + Livewire stack
  ```bash
  composer require laravel/jetstream
  php artisan jetstream:install livewire --teams
  ```

- [x] Install Node dependencies and build assets
  ```bash
  npm install && npm run build
  ```

- [x] Configure SQLite database — edit `.env`
  ```env
  DB_CONNECTION=sqlite
  DB_DATABASE=/absolute/path/to/dot-engage/database/database.sqlite
  ```

- [x] Create the SQLite file
  ```bash
  touch database/database.sqlite
  ```

- [x] Run base migrations (Jetstream + Teams)
  ```bash
  php artisan migrate
  ```

---

## Phase 1 — Core Models & Migrations

### 1.1 Contracts

- [x] Create Contract model + migration + resource controller
  ```bash
  php artisan make:model Contract -mrc
  ```

- [x] Create ContractSignature model + migration
  ```bash
  php artisan make:model ContractSignature -m
  ```

- [x] Create ContractVersion model + migration (version history)
  ```bash
  php artisan make:model ContractVersion -m
  ```

### 1.2 Messaging

- [x] Create Conversation model + migration
  ```bash
  php artisan make:model Conversation -m
  ```

- [x] Create ConversationParticipant pivot model + migration
  ```bash
  php artisan make:model ConversationParticipant -m
  ```

- [x] Create Message model + migration
  ```bash
  php artisan make:model Message -mc
  ```

- [x] Create MessageAttachment model + migration
  ```bash
  php artisan make:model MessageAttachment -m
  ```

### 1.3 Video Sessions

- [x] Create VideoSession model + migration
  ```bash
  php artisan make:model VideoSession -m
  ```

- [x] Create VideoSessionSignature model + migration (signatures captured during calls)
  ```bash
  php artisan make:model VideoSessionSignature -m
  ```

---

## Phase 2 — Policies & Authorization

- [x] Create Contract policy
  ```bash
  php artisan make:policy ContractPolicy --model=Contract
  ```

- [x] Create Conversation policy
  ```bash
  php artisan make:policy ConversationPolicy --model=Conversation
  ```

- [x] Create Message policy
  ```bash
  php artisan make:policy MessagePolicy --model=Message
  ```

- [x] Create VideoSession policy
  ```bash
  php artisan make:policy VideoSessionPolicy --model=VideoSession
  ```

---

## Phase 3 — Livewire Components

### 3.1 Dashboard

- [x] Team dashboard component
  ```bash
  php artisan make:livewire Dashboard/TeamDashboard
  ```

### 3.2 Contracts

- [x] Contract list (browse all contracts in team)
  ```bash
  php artisan make:livewire Contracts/ContractList
  ```

- [x] Contract create/upload wizard
  ```bash
  php artisan make:livewire Contracts/ContractWizard
  ```

- [x] Contract detail viewer (PDF/document preview)
  ```bash
  php artisan make:livewire Contracts/ContractViewer
  ```

- [x] Contract signature pad (canvas-based e-signature)
  ```bash
  php artisan make:livewire Contracts/SignaturePad
  ```

- [x] Contract version history panel
  ```bash
  php artisan make:livewire Contracts/VersionHistory
  ```

- [x] Contract share modal (invite team members / external users)
  ```bash
  php artisan make:livewire Contracts/ShareModal
  ```

### 3.3 Messaging

- [x] Conversation list sidebar
  ```bash
  php artisan make:livewire Chat/ConversationList
  ```

- [x] Conversation thread (messages window)
  ```bash
  php artisan make:livewire Chat/ConversationThread
  ```

- [x] New conversation modal
  ```bash
  php artisan make:livewire Chat/NewConversation
  ```

- [x] Message composer with file attachment support
  ```bash
  php artisan make:livewire Chat/MessageComposer
  ```

- [x] Unread message badge counter
  ```bash
  php artisan make:livewire Chat/UnreadBadge
  ```

### 3.4 Video Sessions

- [x] Video session launcher (create / join)
  ```bash
  php artisan make:livewire Video/SessionLauncher
  ```

- [x] Video session room (WebRTC wrapper)
  ```bash
  php artisan make:livewire Video/SessionRoom
  ```

- [x] In-call document viewer overlay
  ```bash
  php artisan make:livewire Video/InCallDocumentViewer
  ```

- [x] In-call signature capture panel
  ```bash
  php artisan make:livewire Video/InCallSignaturePad
  ```

- [x] Session participant list
  ```bash
  php artisan make:livewire Video/ParticipantList
  ```

### 3.5 Notifications

- [x] Real-time notification bell
  ```bash
  php artisan make:livewire Notifications/NotificationBell
  ```

- [x] Notification tray dropdown
  ```bash
  php artisan make:livewire Notifications/NotificationTray
  ```

---

## Phase 4 — Events & Listeners (Real-time)

- [x] MessageSent event
  ```bash
  php artisan make:event MessageSent
  ```

- [x] ContractShared event
  ```bash
  php artisan make:event ContractShared
  ```

- [x] ContractSigned event
  ```bash
  php artisan make:event ContractSigned
  ```

- [x] VideoSessionStarted event
  ```bash
  php artisan make:event VideoSessionStarted
  ```

- [x] VideoSessionEnded event
  ```bash
  php artisan make:event VideoSessionEnded
  ```

- [x] SignatureRequestedDuringCall event
  ```bash
  php artisan make:event SignatureRequestedDuringCall
  ```

- [x] Create listeners
  ```bash
  php artisan make:listener SendMessageNotification --event=MessageSent
  php artisan make:listener NotifyContractShared --event=ContractShared
  php artisan make:listener NotifyContractSigned --event=ContractSigned
  php artisan make:listener LogVideoSession --event=VideoSessionEnded
  ```

---

## Phase 5 — Notifications

- [x] New message notification
  ```bash
  php artisan make:notification NewMessageNotification
  ```

- [x] Contract shared notification
  ```bash
  php artisan make:notification ContractSharedNotification
  ```

- [x] Contract signed notification
  ```bash
  php artisan make:notification ContractSignedNotification
  ```

- [x] Signature requested notification (during call)
  ```bash
  php artisan make:notification SignatureRequestedNotification
  ```

- [x] Video session invite notification
  ```bash
  php artisan make:notification VideoSessionInviteNotification
  ```

---

## Phase 6 — Jobs (Background Processing)

- [x] Process uploaded contract (PDF parsing / thumbnail generation)
  ```bash
  php artisan make:job ProcessContractUpload
  ```

- [x] Generate signed contract PDF (merge signatures into document)
  ```bash
  php artisan make:job GenerateSignedContractPdf
  ```

- [x] Send contract via email after signing
  ```bash
  php artisan make:job DispatchSignedContractEmail
  ```

- [x] Archive expired video session
  ```bash
  php artisan make:job ArchiveVideoSession
  ```

---

## Phase 7 — Mail

- [x] Contract invitation email
  ```bash
  php artisan make:mail ContractInvitationMail --markdown=emails.contract-invitation
  ```

- [x] Signed contract delivery email
  ```bash
  php artisan make:mail SignedContractMail --markdown=emails.signed-contract
  ```

- [x] Video session invite email
  ```bash
  php artisan make:mail VideoSessionInviteMail --markdown=emails.video-invite
  ```

---

## Phase 8 — Commands (Artisan Maintenance)

- [x] Clean up expired video sessions
  ```bash
  php artisan make:command CleanExpiredVideoSessions
  ```

- [x] Re-process failed contract uploads
  ```bash
  php artisan make:command RetryFailedContractUploads
  ```

- [x] Generate monthly activity report per team
  ```bash
  php artisan make:command GenerateTeamActivityReport
  ```

---

## Phase 9 — Seeders & Factories

- [x] User factory (Jetstream already provides base; extend for Dot.Engage)
  ```bash
  php artisan make:factory ContractFactory --model=Contract
  php artisan make:factory MessageFactory --model=Message
  php artisan make:factory ConversationFactory --model=Conversation
  ```

- [x] Demo seeder
  ```bash
  php artisan make:seeder DemoDataSeeder
  ```

- [x] Run seeders
  ```bash
  php artisan db:seed --class=DemoDataSeeder
  ```

---

## Phase 10 — Storage & File Handling

- [x] Create storage symlink
  ```bash
  php artisan storage:link
  ```

- [x] Configure storage disks for contracts, signatures, and attachments in `config/filesystems.php`
  > Add `contracts`, `signatures`, and `attachments` disks pointing to `storage/app/private/*`

---

## Phase 11 — Broadcasting (Real-time Channels)

- [x] Install Laravel Reverb (self-hosted WebSocket server)
  ```bash
  composer require laravel/reverb
  php artisan reverb:install
  ```

- [x] Publish broadcasting config
  ```bash
  php artisan vendor:publish --provider="Laravel\Reverb\ReverbServiceProvider"
  ```

- [x] Define channels in `routes/channels.php`
  > Channels needed:
  > - `App.Models.User.{id}` — private user notifications
  > - `team.{teamId}.chat` — team-wide chat presence
  > - `conversation.{conversationId}` — private conversation thread
  > - `video-session.{sessionId}` — video room signalling + signature events

---

## Phase 12 — Routes

- [x] Define web routes in `routes/web.php`
  > Key route groups:
  > - `/dashboard` — team dashboard
  > - `/contracts` — CRUD + share + sign
  > - `/chat` — conversations + messages
  > - `/video/{room}` — video room

- [x] Define API routes in `routes/api.php`
  > Key endpoints:
  > - `POST /api/signatures` — save signature data (base64 canvas)
  > - `GET /api/contracts/{contract}/pdf` — stream PDF
  > - `POST /api/video/token` — generate WebRTC session token

---

## Phase 13 — Third-Party Integrations

- [x] Install Spatie Media Library (document/attachment storage)
  ```bash
  composer require spatie/laravel-medialibrary
  php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"
  php artisan migrate
  ```

- [x] Install Spatie Laravel PDF (or barryvdh/laravel-dompdf) for signed contract generation
  ```bash
  composer require barryvdh/laravel-dompdf
  php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
  ```

- [x] Install a WebRTC signalling solution (e.g. agora-io SDK or use Reverb for signalling + daily.co embed)
  ```bash
  npm install @daily-co/daily-js
  # or
  npm install agora-rtc-sdk-ng
  ```

- [x] Install Alpine.js canvas signature plugin (for in-browser signature pads)
  ```bash
  npm install signature_pad
  ```

---

## Phase 14 — Testing

- [x] Create feature tests
  ```bash
  php artisan make:test ContractTest
  php artisan make:test ChatTest
  php artisan make:test VideoSessionTest
  php artisan make:test SignatureTest
  ```

- [x] Create unit tests
  ```bash
  php artisan make:test ContractServiceTest --unit
  php artisan make:test SignatureMergeTest --unit
  ```

- [x] Run full test suite
  ```bash
  php artisan test
  ```

---

## Phase 15 — Final Checks

- [x] Optimise config / routes / views for production
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

- [x] Clear all caches during development
  ```bash
  php artisan optimize:clear
  ```

- [x] Start Reverb WebSocket server
  ```bash
  php artisan reverb:start
  ```
  > **Note:** Requires `pcntl` PHP extension (compile with `--enable-pcntl`). Not available in this devcontainer; works on standard production/dev servers.

- [x] Start queue worker
  ```bash
  php artisan queue:work --queue=default,contracts,notifications
  ```

- [x] Start local dev server
  ```bash
  php artisan serve
  npm run dev
  ```

---

## Summary Checklist

| Area | Models | Livewire Components | Events | Jobs |
|---|---|---|---|---|
| Contracts | 3 | 6 | 2 | 3 |
| Chat | 4 | 5 | 1 | 0 |
| Video + Signing | 2 | 5 | 3 | 1 |
| Notifications | — | 2 | — | — |
| **Total** | **9** | **18** | **6** | **4** |

---

*Generated for Dot.Engage — © All rights reserved*
