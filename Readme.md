# Recruitment Task – Laravel + Inertia + React

This project is a Laravel application with an Inertia + React frontend.  


## Requirements

- **PHP** >= 8.3
- **Node.js** >= 22
- **Composer** (latest)
- **npm** (comes with Node.js)

## Installation

1. **Clone the repository**
```bash
   git clone https://github.com/dawidsurgota3/zadanie-rekrutacyjne.git
   cd zadanie-rekrutacyjne
```   
2. **Install PHP dependencies**
```bash
    composer install
```
3. **Install JavaScript dependencies**
```bash
    npm install
```
4. **Set up environment file**   
```bash
    cp .env.example .env
```
5. **Run the development server**   
```bash
   composer dev
```

This will start both the Laravel backend and the Vite dev server for the frontend.

## Running Tests
Before running tests, build the frontend assets:
```bash
    npm run build
```
Then run the Laravel test:
```bash
    php artisan test
```
