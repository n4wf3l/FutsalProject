# FutsalProject - SaaS Platform

This is a fully customizable SaaS platform built with **Laravel** and **Tailwind CSS**, designed for futsal clubs. The system allows for quick deployment for any club with personalized logos, site names, and club data. An admin panel is available for seamless content management.

> **Note:** This platform has been successfully deployed for **Dina Kenitra FC**. Other clubs can subscribe to this SaaS solution upon request.

## 🚀 **Features**
- 100% customizable (logo, club name, data, etc.)
- Admin dashboard for managing content
- Multi-club deployment ready
- Stripe integration for payments, including **ticket sales**
- Responsive UI with Tailwind CSS
- **Multilingual support** for global accessibility

---

## ⚙️ **Installation Guide**

### 1️⃣ **Clone the Repository**
```bash
git clone <repository_url>
cd <project_directory>
```

### 2️⃣ **Install Backend Dependencies**
```bash
composer install
```

### 3️⃣ **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

### 4️⃣ **Database Migration and Seeding**
```bash
php artisan migrate:fresh --seed
```

### 5️⃣ **Storage Linking**
```bash
php artisan storage:link
```

### 6️⃣ **Run the Development Server**
```bash
php artisan serve
```

---

### 7️⃣ **Frontend Setup** (New Terminal)
```bash
npm install
npm run dev
```

---

## 📄 **Configuration**

Open the `.env` file and configure the following:

- **Database Setup:**
  ```env
  DB_DATABASE=your_database
  DB_USERNAME=your_username
  DB_PASSWORD=your_password
  ```

- **Stripe Integration:**
  ```env
  STRIPE_KEY=your_stripe_key
  STRIPE_SECRET=your_stripe_secret
  ```

- **Email Configuration:**
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.mailtrap.io
  MAIL_PORT=2525
  MAIL_USERNAME=your_email_username
  MAIL_PASSWORD=your_email_password
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS=your_email@example.com
  MAIL_FROM_NAME="Your Club Name"
  ```

---

## 🔐 **Admin Login**

After seeding, use the default admin credentials:
- **Email:** `admin@example.com`
- **Password:** `password`

*(Change these credentials immediately after logging in for the first time.)*

---

## 🌍 **Multilingual Support**

The platform supports multiple languages to cater to diverse audiences. You can easily add new languages via the localization files in the `resources/lang` directory.

---

## 🧪 **Testing**
Run tests to ensure everything is working correctly:
```bash
php artisan test
```

---

## 📦 **Deployment**

1. Set up your production server.
2. Update your `.env` with production credentials.
3. Run migrations:
   ```bash
   php artisan migrate --force
   ```
4. Compile assets:
   ```bash
   npm run build
   ```
5. Restart your server if needed.

---

## 🤝 **Contributing**
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

---

## 📧 **Support**
If you encounter any issues, please contact the development team at `info@nainnovations.be`.

---

## 🔑 **Subscription Model**
This project is a subscription-based SaaS solution acquired by **Dina Kenitra FC**. Other futsal clubs can subscribe to this service upon request. For subscription inquiries, please contact `info@nainnovations.be`.
