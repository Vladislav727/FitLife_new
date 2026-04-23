<div align="center">

# 🏋️ FitLife

### ⚡ Personal Fitness, Wellness & Social Platform

<br/>

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind](https://img.shields.io/badge/Tailwind-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

[![Tests](https://github.com/Ichiro149/FitLife_new/actions/workflows/tests.yml/badge.svg)](https://github.com/Ichiro149/FitLife_new/actions/workflows/tests.yml)
[![Pest](https://img.shields.io/badge/Pest-4.x-F472B6?style=flat-square&logo=php)](https://pestphp.com)
[![Code Style](https://img.shields.io/badge/Code%20Style-Laravel%20Pint-orange?style=flat-square)](https://laravel.com/docs/pint)

<br/>

<p align="center">
  <a href="#-quick-start">Quick Start</a> •
  <a href="#-features">Features</a> •
  <a href="#-messaging-system">Messaging</a> •
  <a href="#-tech-stack">Tech Stack</a> •
  <a href="#-testing">Testing</a> •
  <a href="#-api-integrations">API</a> •
  <a href="#-docker">Docker</a>
</p>

<br/>

**Complete fitness tracking solution with Nutrition, Sleep, Goals, Progress Photos, Real-time Messaging & Social Community**

<br/>

> 🌍 **Multi-language**: Supports English, Russian, and Latvian

</div>

<br/>

---

<br/>

## 🚀 Quick Start

```bash
# Clone & Install
git clone https://github.com/Ichiro149/FitLife_new.git
cd FitLife_new && composer install && npm install

# Setup
cp .env.example .env && php artisan key:generate
php artisan migrate --seed && php artisan storage:link

# Run
npm run build && php artisan serve
```

**Visit:** http://localhost:8000

<br/>

### 🐳 Docker

```bash
docker build -t fitlife .
docker run -p 10000:10000 fitlife
```

The Docker image uses `php:8.2-cli` with PHP extensions for MySQL/PostgreSQL, GD image processing, and includes a full Node.js build step.

<br/>

### 🔧 Dev Mode (concurrent)

```bash
composer dev
```

Starts **4 processes** simultaneously: Laravel server, queue worker, Pail log viewer, and Vite dev server.

<br/>

---

<br/>

## ✨ Features

<table>
<tr>
<td width="50%">

### 🍎 Health Tracking

- 🍽️ **Meal Tracker** — Log meals with CalorieNinjas API + local database of 16 foods
- 💧 **Water Tracker** — Daily hydration monitoring with 2000ml goal
- 😴 **Sleep Tracker** — Duration tracking with overnight detection algorithm
- 🔥 **Calorie Calculator** — Mifflin-St Jeor BMR formula, TDEE & macro split
- 📊 **Progress Photos** — Visual transformation gallery with descriptions
- 🎯 **Goals** — Custom fitness goals with progress logging & completion celebration
- 📋 **Biography** — Health profile (age, height, weight, gender)

</td>
<td width="50%">

### 👥 Social Features

- 📝 **Posts** — Share updates with photo/video, like/dislike reactions
- 💬 **Comments** — TikTok-style flat threading with @mentions
- 👫 **Friends** — Bidirectional friend system (send/accept/reject)
- 👤 **Follow System** — Follow/unfollow with mutual follow detection
- 🟢 **Online Status** — Real-time "last seen" tracking (3-min threshold)
- 📅 **Activity Calendar** — 25 activity types with completion tracking
- 🔔 **Notifications** — Reactions, @mentions, group invites
- 🔥 **Trending** — Hot sort algorithm with time-decay ranking

</td>
</tr>
</table>

<br/>

---

<br/>

## 💬 Messaging System

Full-featured messaging with both **1:1 Direct Messages** and **Group Chats**.

<table>
<tr>
<td width="50%">

### 📨 Direct Messages

- Start conversations (requires mutual follow)
- Text, image, video, file & voice messages
- Reply-to threading
- Message editing & deletion
- Emoji reactions
- Message forwarding (to DMs or groups)
- Pinned messages
- Message favorites/bookmarks
- In-chat search
- Read receipts & typing indicators
- Per-chat themes

</td>
<td width="50%">

### 👥 Group Chats

- Create groups with name, description & avatar
- Role system: **Owner → Admin → Member**
- Group invitations (mutual-follow gated)
- All DM features plus:
- 📊 **In-chat polls** (anonymous & multi-choice)
- Admin message moderation (delete any message)
- Admin-only pinning
- Member management (promote/demote/remove)
- Group settings (name, description, avatar)

</td>
</tr>
</table>

<br/>

### 💡 Messaging Features Detail

| Feature | Description |
|:--------|:------------|
| 🎤 **Voice Messages** | Audio recording with duration tracking |
| ↩️ **Message Forwarding** | Cross-type forwarding between DMs and groups |
| 📌 **Pinned Messages** | Pin important messages (admin-only in groups) |
| ⭐ **Favorites** | Bookmark messages across all chats |
| 🔍 **Search** | Full-text search within any conversation |
| ⌨️ **Typing Indicators** | Cache-based (4s TTL), shows who is typing |
| 🎨 **Chat Themes** | Customizable themes per user per chat |
| 👀 **Read Receipts** | Track message read status |
| 🔄 **Long Polling** | Real-time message delivery with online status |

<br/>

---

<br/>

### 📅 Activity Calendar — 25 Activity Types

<table>
<tr>
<td width="25%" align="center">

**🏃 Cardio**

Running, Cycling,
Swimming, Walking,
Rowing, Cardio

</td>
<td width="25%" align="center">

**🧘 Wellness**

Yoga, Meditation,
Dance, Pilates,
Stretching

</td>
<td width="25%" align="center">

**💪 Strength**

Weightlifting, Boxing,
CrossFit, Gym,
Martial Arts, Climbing

</td>
<td width="25%" align="center">

**🌿 Other**

Hiking, Tennis,
Basketball, Soccer,
Rest, Recovery

</td>
</tr>
</table>

<br/>

### 🔐 Role System

| Role | Access Level |
|:-----|:-------------|
| 🔴 **Super Admin** | Full system access, can manage admins, protected from deletion |
| 🟠 **Admin** | User management, content moderation, events management, statistics |
| 🟢 **User** | Track health, post updates, message, connect with friends |

<br/>

### 🌍 Multi-language Support

<table>
<tr>
<td align="center">🇬🇧 <b>English</b></td>
<td align="center">🇷🇺 <b>Русский</b></td>
<td align="center">🇱🇻 <b>Latviešu</b></td>
</tr>
</table>

Language preference is stored per user and applied automatically via middleware. 18 translation files per language covering all modules.

<br/>

---

<br/>

## 🔬 API Integrations

| Integration | Usage |
|:------------|:------|
| **CalorieNinjas API** | Nutrition data lookup for food items with full macro breakdown (calories, protein, fats, carbs). Falls back to local database of 16 common foods |
| **OpenAI** | `openai-php/laravel` package installed for AI-powered features |

<br/>

### 🧮 Algorithms

| Algorithm | Details |
|:----------|:--------|
| **Mifflin-St Jeor BMR** | `10 × weight + 6.25 × height - 5 × age + 5`, with 4 activity multipliers (1.2–1.725), ±500 kcal goal adjustment |
| **Macro Split** | Protein: 1.8g/kg, Fat: 25% of calories, Carbs: remainder |
| **Hot Sort** | Post ranking: `(likes + comments) / (hours_since_post + 1)` — time-decay engagement scoring |
| **Overnight Sleep** | Auto-detects when end_time < start_time, adds a day for correct duration |
| **Online Status** | Updates `last_seen_at` throttled to 1/min, online if seen within 3 minutes |

<br/>

---

<br/>

## 🛠️ Tech Stack

<table>
<tr>
<td align="center" width="20%">
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="80"/><br/>
<b>Laravel 12</b><br/>
<sub>Backend Framework</sub>
</td>
<td align="center" width="20%">
<img src="https://www.vectorlogo.zone/logos/tailwindcss/tailwindcss-icon.svg" width="50"/><br/>
<b>Tailwind CSS</b><br/>
<sub>Styling</sub>
</td>
<td align="center" width="20%">
<img src="https://alpinejs.dev/alpine_long.svg" width="80"/><br/>
<b>Alpine.js</b><br/>
<sub>JavaScript</sub>
</td>
<td align="center" width="20%">
<img src="https://vitejs.dev/logo.svg" width="50"/><br/>
<b>Vite 7</b><br/>
<sub>Build Tool</sub>
</td>
<td align="center" width="20%">
<img src="https://pestphp.com/www/assets/logo.svg" width="50"/><br/>
<b>Pest PHP 4</b><br/>
<sub>Testing</sub>
</td>
</tr>
</table>

<br/>

| Layer | Technologies |
|:------|:------------|
| **Backend** | PHP 8.2+, Laravel 12, Blade Templates, Eloquent ORM |
| **Frontend** | Tailwind CSS 3, Alpine.js 3, Vite 7, Axios |
| **Database** | MySQL 8.0+ / PostgreSQL (prod), SQLite (testing) |
| **Testing** | Pest PHP 4.x with Pest Browser plugin, parallel execution |
| **Auth** | Laravel Breeze (email verification, password reset) |
| **Code Style** | Laravel Pint |
| **Containerization** | Docker (php:8.2-cli, multi-DB support) |
| **Packages** | openai-php/laravel, Laravel Sail, Laravel Pail |

<br/>

### 🧩 Middleware Stack

| Middleware | Purpose |
|:-----------|:--------|
| `AdminMiddleware` | Restricts admin routes to `admin`/`super_admin` roles |
| `SetLocale` | Sets app language from user preference → session → fallback `en` |
| `TrackLastSeen` | Updates `last_seen_at` (throttled to 1 write/min) for online status |

<br/>

---

<br/>

## 🧪 Testing

<div align="center">

```
✅ 245 Tests | 32 Test Files | ⚡ Parallel Execution
```

</div>

<br/>

```bash
# Run all tests
./vendor/bin/pest

# Parallel execution (faster)
./vendor/bin/pest --parallel

# With coverage
./vendor/bin/pest --coverage
```

<br/>

### Test Coverage

<table>
<tr><td>

#### Feature Tests (24 files)

| Module | What's Covered |
|:-------|:---------------|
| 🔐 Auth | Registration, login, password reset, email verification |
| 🔒 Authorization | Policy checks, role-based access |
| 🍽️ Meals | CRUD, calorie calculation, nutrition lookup |
| 😴 Sleep | Logging, overnight detection, duration |
| 💧 Water | Daily logging, history, goal tracking |
| 🎯 Goals | CRUD, progress logging, completion |
| 📝 Posts | CRUD, media, reactions, views, comments |
| 💬 Comments | CRUD, threading, reactions |
| 👫 Friends | Requests, accept/reject, removal |
| 📅 Calendar | Events CRUD, filtering, completion |
| 📊 Progress | Photo upload, gallery, descriptions |
| 👤 Profile | Edit, biography, avatar, banner |
| 🛡️ Admin | Dashboard, user/post/event management |

</td><td>

#### Unit Tests (8 files)

| Module | What's Covered |
|:-------|:---------------|
| 🔥 Calories | BMR calculation, macro split |
| 📅 Dates | Date helper functions |
| 🎯 Goals | Progress percentage calculation |
| 🏥 Health | Health metrics computation |
| 😴 Sleep | Duration calculation, overnight |
| ✅ Validation | Input validation rules |
| 💧 Water | Daily intake calculation |

</td></tr>
</table>

<br/>

### Model Tests

| Model | Relationships & Logic Tested |
|:------|:----------------------------|
| User | Friends, follows, online status, roles |
| Post | Likes, views, comments, media |
| Comment | Threading, parent/reply, reactions |
| Goal / GoalLog | Progress tracking, completion |
| Sleep / MealLog / WaterLog | Data integrity, user association |
| Biography / Calendar / Friend | CRUD, model relationships |

<br/>

---

<br/>

## 📁 Project Structure

```
app/
├── 📂 Http/
│   ├── Controllers/           # 20 controllers
│   │   ├── Admin/             # AdminPanelController
│   │   ├── Auth/              # 9 auth controllers (Breeze)
│   │   ├── ConversationController  # 1:1 messaging
│   │   ├── GroupController         # Group chats & polls
│   │   ├── NotificationController  # Notifications & invites
│   │   ├── PostController          # Social feed
│   │   ├── FoodController          # Nutrition tracking
│   │   ├── FollowController        # Follow system
│   │   └── ...                     # 11 more controllers
│   ├── Middleware/
│   │   ├── AdminMiddleware         # Role-based access
│   │   ├── SetLocale               # i18n middleware
│   │   └── TrackLastSeen           # Online status
│   └── Requests/              # Form validation
│
├── 📂 Models/                 # 30 Eloquent models
│   ├── User, Post, Comment, Like, CommentLike
│   ├── Goal, GoalLog, Sleep, MealLog, Food, WaterLog
│   ├── Progress, Biography, Calendar
│   ├── Follow, Friend, PostView, Notification
│   ├── Conversation, ConversationMessage
│   ├── Group, GroupMember, GroupMessage, GroupInvite
│   ├── GroupPoll, GroupPollOption, GroupPollVote
│   └── MessageFavorite, MessageReaction, ChatTheme
│
├── 📂 Policies/               # PostPolicy (owner/admin checks)
└── 📂 View/Components/        # Blade layout components

database/
├── 📂 factories/              # 5 factories (User, Post, Goal, GoalLog, Like)
├── 📂 migrations/             # 48 migrations
└── 📂 seeders/                # User, Goal, Post, Friend seeders

resources/
├── 📂 css/                    # Stylesheets
├── 📂 js/                     # Alpine.js + Axios
├── 📂 lang/                   # 18 translation files × 3 languages
│   ├── en/                    # English
│   ├── ru/                    # Russian
│   └── lv/                    # Latvian
└── 📂 views/                  # Blade templates
    ├── admin/                 # Admin panel views
    ├── chats/                 # Unified chat hub
    ├── conversations/         # DM views
    ├── groups/                # Group chat views
    ├── notifications/         # Notification center
    ├── posts/                 # Social feed
    ├── profile/               # User profiles
    ├── foods/, sleep/, water/ # Health trackers
    ├── goals/, progress/      # Goals & photos
    ├── activity-calendar/     # Calendar
    └── layouts/, components/  # Shared UI

tests/
├── 📂 Feature/                # 24 feature test files
└── 📂 Unit/                   # 8 unit test files
```

<br/>

---

<br/>

## ⚙️ Configuration

### Environment Variables

```env
# App
APP_NAME=FitLife
APP_URL=http://localhost:8000

# Database (MySQL or PostgreSQL)
DB_CONNECTION=mysql
DB_DATABASE=fitlife

# CalorieNinjas API (for nutrition lookup)
CALORIENINJAS_API_KEY=your-api-key

# OpenAI (optional)
OPENAI_API_KEY=your-openai-key

# Mail (optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
```

<br/>

---

<br/>

## 🔌 Main Routes

<details>
<summary><b>🏠 Dashboard & Tracking</b></summary>

| Route | Description |
|:------|:------------|
| `/dashboard` | Main dashboard with all health stats, upcoming events |
| `/tracker/foods` | Meal tracker with CalorieNinjas API lookup |
| `/tracker/foods/history` | Meal history |
| `/tracker/sleep` | Sleep tracker with overnight detection |
| `/tracker/water` | Water tracker with daily goal |
| `/goals` | Goals management with progress logging |
| `/goals/{id}/log` | Log progress for a specific goal |
| `/progress-photos` | Progress photos gallery |
| `/calorie-calculator` | BMR & macro calculator |
| `/biography` | Health profile (age, height, weight) |

</details>

<details>
<summary><b>👥 Social</b></summary>

| Route | Description |
|:------|:------------|
| `/posts` | Community feed (newest / top / hot sort) |
| `/posts/search-users` | User search for @mentions |
| `/profile/{username}` | User profile with follow/friend actions |
| `/profile` | Edit own profile |
| `/friends/{username}` | Send/accept/remove friend request |
| `/follow/{username}` | Toggle follow |
| `/profile/{username}/followers` | Followers list |
| `/profile/{username}/following` | Following list |

</details>

<details>
<summary><b>💬 Messaging</b></summary>

| Route | Description |
|:------|:------------|
| `/chats` | Unified chat hub (DMs + groups) |
| `/conversations` | Direct message list |
| `/conversations/start/{username}` | Start new DM (requires mutual follow) |
| `/conversations/{id}` | Conversation view with full messaging |
| `/groups` | Group list |
| `/groups/create` | Create new group |
| `/groups/{id}` | Group chat view |
| `/groups/{id}/invite` | Invite members |
| `/favorites` | All favorited messages |

</details>

<details>
<summary><b>🔔 Notifications</b></summary>

| Route | Description |
|:------|:------------|
| `/notifications` | Notification center with group invites |
| `/notifications/read` | Mark all as read |
| `/notifications/invite/{id}/accept` | Accept group invite |
| `/notifications/invite/{id}/decline` | Decline group invite |

</details>

<details>
<summary><b>📅 Calendar & Settings</b></summary>

| Route | Description |
|:------|:------------|
| `/calendar` | Activity calendar (25 activity types) |
| `/calendar/events` | JSON API for calendar events |
| `/settings` | Language settings |
| `/privacy-policy` | Privacy policy page |
| `/terms-of-service` | Terms of service page |

</details>

<details>
<summary><b>🛡️ Admin Panel</b></summary>

| Route | Description |
|:------|:------------|
| `/admin/dashboard` | Admin dashboard with stats |
| `/admin/users` | User management (view/edit/delete) |
| `/admin/users/{username}` | View user details |
| `/admin/users/{username}/edit` | Edit user (role assignment) |
| `/admin/posts` | Posts moderation |
| `/admin/events` | Events management |
| `/admin/statistics` | Platform statistics |

</details>

<br/>

---

<br/>

## 📊 Database Schema

30 models across 48 migrations:

| Domain | Models |
|:-------|:-------|
| **Users & Auth** | User, Biography, Follow, Friend |
| **Health Tracking** | Food, MealLog, Sleep, WaterLog, Progress, Goal, GoalLog |
| **Social** | Post, Comment, Like, CommentLike, PostView, Notification |
| **Calendar** | Calendar (25 activity types) |
| **Messaging** | Conversation, ConversationMessage |
| **Groups** | Group, GroupMember, GroupMessage, GroupInvite |
| **Polls** | GroupPoll, GroupPollOption, GroupPollVote |
| **Shared** | MessageReaction, MessageFavorite, ChatTheme |

Key patterns: **Polymorphic relations** for reactions, favorites, themes & notifications. **Bidirectional friendships** with status tracking. **Dual messaging** (DM + Group) with shared polymorphic features.

<br/>

---

<br/>

## 📝 License

This project is open-sourced under the [MIT License](https://opensource.org/licenses/MIT).

<br/>

---

<br/>

<div align="center">

### 🌟 Star this repo if you find it helpful!

<br/>

**Built with ❤️ using**

<br/>

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Tailwind](https://img.shields.io/badge/Tailwind-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev)
[![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![Pest](https://img.shields.io/badge/Pest-F472B6?style=for-the-badge&logo=php&logoColor=white)](https://pestphp.com)
[![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://docker.com)

<br/>

<sub>Made by <a href="https://github.com/Ichiro149">@Ichiro149</a></sub>

</div>
