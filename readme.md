# ImagineChat

This project implements a simple real-time chat application using PHP and Laravel, backed by a PostgreSQL database. Users authenticate via GitHub OAuth, after which they can participate in a shared chat channel where messages are delivered in real time using Server-Sent Events (SSE).

The backend persists users and message history in PostgreSQL and uses the LISTEN/NOTIFY mechanism to efficiently propagate new messages to connected clients. The frontend uses Laravel Livewire to build an interactive chat interface without heavy JavaScript. Livewire components handle message input, validation, and initial message loading, while keeping frontend logic closely aligned with backend state. The application is designed with clean separation of concerns, secure data handling, and a deployment-ready setup using Nginx and PHP-FPM, demonstrating a practical and scalable approach to real-time features in a traditional PHP stack.

## Deployment

### Requirements

Make sure that the following is installed on your computer:
  - [Docker](https://docs.docker.com/engine/install/)

[Create](https://docs.github.com/en/apps/oauth-apps/building-oauth-apps/creating-an-oauth-app) an OAuth app on GitHub.
Specify:
 - Homepage URL: `http://localhost:8000`
 - Authorization callback URL: `http://localhost:8080/auth/github/callback`

Than copy Client id, Client secret and Callback URL to the "github" section in [services](./config/services.php).

### Instructions

1) Clone the repository: `git clone https://github.com/zmazk123/ImagineChat.git`

2) Build and deploy database and ImagineChat: `docker compose build && docker compose up --force-recreate -d`

3) If you are running for the first time, apply migration: `php artisan migrate` (alternatively [generate SQL scripts](https://stackoverflow.com/questions/31263637/how-to-convert-laravel-migrations-to-raw-sql-scripts) from migrations and run them directly in the database)

Imagine chat should be available on: `http://localhost:8080`



## Auth

The application supports two authentication methods: traditional username/password login and OAuth-based authentication via GitHub. This allows users to either register locally or sign in using their existing GitHub account.

GitHub OAuth is implemented using Laravel Socialite, Laravel’s official authentication library for third-party OAuth providers. Socialite abstracts the OAuth2 flow, handling redirects, access token exchange, and retrieval of user profile data, which simplifies secure integration with external identity providers while keeping the application code clean and maintainable.

## Security

Security considerations are built into the application by leveraging Laravel’s defaults and best practices. Blade templates automatically escape output, providing protection against cross-site scripting (XSS) attacks when rendering user-generated content such as chat messages. Database interactions are handled exclusively through Eloquent ORM and parameterized queries, which protect against SQL injection by ensuring that user input is never interpolated directly into raw SQL.

## Future improvements

- Add tests for TDD
- In case it would be a high traffic app we could add rate limiting on sending messages to avoid abuse

- Improve frontend user experience
- Use Livewire events for receiving messages for more clean code. Or use a JS framework(Vue, React) 