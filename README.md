# PhePhim - Movie Streaming Website

A full-featured movie streaming platform built with PHP MVC architecture. Features include movie browsing, user authentication (including Google OAuth), favorites, watch history, comments, and an admin panel for content management.

## Features

### User Features
- **Browse Movies** - Filter by genre, country, type (Series/Movies/Theater)
- **Watch Movies** - Stream episodes with multiple video sources
- **User Accounts** - Register, login, Google OAuth integration
- **Favorites** - Save favorite movies and actors
- **Watch History** - Continue watching from where you left off
- **Comments** - Discuss movies with other users
- **Responsive Design** - Works on desktop and mobile

### Admin Features
- **Dashboard** - Statistics and overview
- **Movie Management** - Add, edit, delete movies
- **Crawler** - Auto-import movies from external APIs (PhimAPI, OPhim)
- **TMDB Integration** - Fetch movie details, posters, trailers
- **User Management** - Manage user accounts
- **Activity Logs** - Track admin actions

## Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 8.x (Custom MVC Framework) |
| **Database** | MySQL |
| **Frontend** | HTML, CSS, JavaScript |
| **Authentication** | Session-based + Google OAuth 2.0 |
| **External APIs** | TMDB, PhimAPI, OPhim |

## Project Structure

```
movie/
├── app/
│   ├── Controllers/     # Application controllers
│   ├── Models/          # Database models
│   └── Views/           # View templates
├── configs/
│   ├── configs.php      # Main configuration (gitignored)
│   └── configs.example.php  # Example config template
├── core/                # MVC core framework files
├── public/              # Static assets (CSS, JS, images)
├── router/              # Route definitions
├── vendor/              # Composer dependencies
└── index.php            # Application entry point
```

## Installation

### Prerequisites
- PHP 8.0 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite enabled
- Composer

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/nkhanh23/movie.git
   cd movie
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Create database**
   - Import the SQL schema (if provided) or let the app create tables

4. **Configure the application**
   ```bash
   cp configs/configs.example.php configs/configs.php
   ```
   
   Edit `configs/configs.php` with your settings:
   ```php
   // Database
   const _HOST = 'localhost';
   const _DB = 'your_database';
   const _USER = 'your_username';
   const _PASS = 'your_password';
   
   // Google OAuth (optional)
   const _GOOGLE_CLIENT_ID = 'your_client_id';
   const _GOOGLE_CLIENT_SECRET = 'your_secret';
   
   // TMDB API (optional, for movie data enrichment)
   const _TMDB_API_KEY = 'your_tmdb_api_key';
   ```

5. **Configure Apache Virtual Host** (optional)
   ```apache
   <VirtualHost *:80>
       DocumentRoot "/path/to/movie"
       ServerName movie.local
       <Directory "/path/to/movie">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

6. **Access the application**
   - Frontend: `http://localhost/movie`
   - Admin: `http://localhost/movie/admin`

##  API Keys Setup

### TMDB API
1. Create account at [themoviedb.org](https://www.themoviedb.org/)
2. Go to Settings → API → Request API Key
3. Add key to `configs/configs.php`

### Google OAuth
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create project → APIs & Services → Credentials
3. Create OAuth 2.0 Client ID
4. Add authorized redirect URI: `http://your-domain/auth/google/callback`
5. Add credentials to `configs/configs.php`

## Screenshots

*Coming soon...*

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is for educational purposes.

## Author

**nkhanh23**
- GitHub: [@nkhanh23](https://github.com/nkhanh23)
