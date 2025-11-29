# Crypto Tracker

Welcome to **Crypto Tracker**, a modern application for real-time cryptocurrency tracking. This application allows users to view current prices, historical charts, and manage a personalized watchlist.

## 📸 Screenshots

Here is a preview of the application.

### Home Page
<img width="1728" height="964" alt="image" src="https://github.com/user-attachments/assets/34ae23ee-55a4-4039-94e0-ba676415af1e" />

*Market overview with trending cryptocurrencies.*

### Coin Details
<img width="1728" height="964" alt="image" src="https://github.com/user-attachments/assets/aac77688-5a4e-4afc-a8bc-14f5e6a0c7d2" />


*Interactive charts and detailed information for each cryptocurrency.*

### Watchlist
<img width="1728" height="961" alt="image" src="https://github.com/user-attachments/assets/dba7b23c-5058-4038-84a7-3ab970094844" />

*Your personalized list of favorite coins.*

### Exchanges
<img width="1728" height="959" alt="image" src="https://github.com/user-attachments/assets/5240e092-4dc6-44af-bc0c-593caa4c3ce0" />

### Register
<img width="1728" height="962" alt="image" src="https://github.com/user-attachments/assets/77a84073-21b9-45b4-a9db-79897aa979d1" />



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
