# Crypto Tracker

Welcome to **Crypto Tracker**, a modern application for real-time cryptocurrency tracking. This application allows users to view current prices, historical charts, and manage a personalized watchlist.

## 📸 Screenshots

Here is a preview of the application.

### Home Page
![Home Page](https://via.placeholder.com/800x450?text=Home+Page+Screenshot)
*Market overview with trending cryptocurrencies.*

### Coin Details
![Coin Details](https://via.placeholder.com/800x450?text=Coin+Details+Screenshot)
*Interactive charts and detailed information for each cryptocurrency.*

### Watchlist
![Watchlist](https://via.placeholder.com/800x450?text=Watchlist+Screenshot)
*Your personalized list of favorite coins.*

---

## 🚀 About the Project

This project is a complete cryptocurrency tracking solution that combines a robust backend with a dynamic and reactive frontend.

**Key Features:**
- **Real-time Data:** Instantly updated prices and percentage changes.
- **Interactive Charts:** Price history visualization (24h, 7d, 30d, etc.).
- **Watchlist:** Functionality to save your favorite coins (requires local persistence or backend).
- **Modern Interface:** Clean and responsive design adapted for mobile and desktop devices.

## 🛠️ Tech Stack

- **Frontend:** [SvelteKit](https://kit.svelte.dev/), TypeScript, TailwindCSS.
- **Backend:** [Laravel](https://laravel.com/), PHP.
- **Database:** SQLite.

---

## ⚙️ Setup & Installation

Follow these steps to run the project in your local environment.

### Prerequisites
Ensure you have installed:
- [PHP](https://www.php.net/) (v8.1 or higher)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) and NPM

### 1. Backend Setup (Laravel)

Navigate to the backend folder and configure dependencies.

```bash
cd backend

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations (SQLite)
# Ensure database/database.sqlite exists or let Laravel create it if prompted.
php artisan migrate

# Start development server
php artisan serve --port=8001
```

The backend will be running at `http://127.0.0.1:8001`.

### 2. Frontend Setup (SvelteKit)

In a new terminal, navigate to the frontend folder.

```bash
cd frontend

# Install Node dependencies
npm install

# Start development server
npm run dev
```

The frontend will generally be available at `http://localhost:5173` (or the port indicated in the console).

---

## 📝 Additional Notes

- **Database:** The project uses SQLite by default, located at `backend/database/database.sqlite`. You do not need to configure an external MySQL/PostgreSQL server.
- **API:** The frontend communicates with the backend via REST API calls. Ensure both servers are running simultaneously.
