

<p align="center">
  <img src="public/images/assetnova-logo.png" width="80" alt="AssetNova Logo">
</p>

<h1 align="center">AssetNova</h1>


<p align="center">
  <strong>Industrial inventory, reimagined.</strong><br>
  Deliver real-time spare parts tracking, procurement intelligence, and seamless multi-company control in one unified platform.
</p>
<img width="1440" height="817" alt="Screenshot 2026-04-13 at 8 35 14 AM" src="https://github.com/user-attachments/assets/0364145f-fa9f-4d2d-b81a-4f6164a44229" />


<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-red.svg" alt="Laravel 12.x">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-blue.svg" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/MySQL-8.0-orange.svg" alt="MySQL 8.0">
  <img src="https://img.shields.io/badge/Status-Production--Ready-brightgreen.svg" alt="Status">
</p>

---

## 🚀 Key Features

### 🏢 Multi-Tenant SaaS Architecture
*   **Data Isolation**: Every company (e.g., Optimum, Caterpillar) operates in a completely isolated environment.
*   **Scoped Dashboards**: Real-time analytics and inventory trajectory tailored to each tenant.
*   **Tenant Branding**: Specific logos and company details reflected in the UI and reports.

### ⚙️ Comprehensive Inventory Management
*   **Parts Directory**: High-fidelity tracking of machine spares with product imagery support.
*   **Transactions Ledger**: Real-time logging of stock ingress (In) and egress (Out) with validation.
*   **Smart Thresholds**: Low-stock alerts and critical asset monitoring.

### 🏗️ Premium Industrial UI/UX
*   **Digital Foreman Design**: A minimalistic, high-performance light theme built with Blade and TailwindCSS.
*   **Mobile Ready**: Fully responsive navigation with slide-in drawers and a quick-access mobile bottom bar.
*   **Dynamic UX**: Real-time search, interactive charts (Chart.js), and glassmorphism elements.

---

## 🛠️ Technology Stack
- **Framework**: Laravel 12.x
- **Database**: MySQL 8.0
- **View Engine**: Blade Templates
- **Styling**: TailwindCSS & Vanilla CSS
- **Interactivity**: Alpine.js / Vanilla JS
- **Charts**: Chart.js 4.4

---

## 💻 Getting Started

### Prerequisites
- PHP 8.2 or higher
- MySQL 8.0
- Composer

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/NirajDN/AssetNova.git
   cd AssetNova
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Configure Environment**
   ```bash
   cp .env.example .env
   # Update DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env
   php artisan key:generate
   ```

4. **Prepare Database**
   ```bash
   php artisan migrate --seed
   ```

5. **Start the server**
   ```bash
   php artisan serve
   ```

---

## 🔒 Security & Validation
*   **Company Scoping**: Every query is strictly filtered by `company_id` using Eloquent global constraints where necessary or manual scoping in controllers.
*   **Audit-Ready**: Every stock movement is logged with a link to the responsible personnel.
*   **Input Guard**: Hardened validation for SKUs, quantities, and file uploads.

---

## 📝 License
AssetNova is open-sourced software licensed under the [MIT license](LICENSE).

<p align="center">
  Building the future of machine operations, one part at a time.
</p>
