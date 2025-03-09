# Laravel API Project

This is a RESTful API built with **Laravel 12**, implementing authentication, CRUD operations, background jobs, and external API integration.

## 🚀 Features

- **User Authentication** (Register, Login, Logout) using **Laravel Sanctum**
- **CRUD Operations** for Posts
- **Weather API Integration** (fetches and caches current weather data)
- **Queued Job for Welcome Emails** (Sends an email when a user registers)
- **Background Job for Weather Updates** (Runs every hour to fetch new weather data)
- **Pagination & Validation**
- **Proper HTTP Status Codes & Error Handling**

---

## 🛠 Installation & Setup

### **1. Clone the Repository**

```bash
git clone https://github.com/your-repo/laravel-api.git
cd laravel-api
```

### **2. Install Dependencies**

```bash
composer install
```

### **3. Set Up Environment Variables**

Copy the `.env.example` file and configure your database and API keys:

```bash
cp .env.example .env
```

Update the following values in `.env`:

```env
APP_NAME=LaravelAPI
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

WEATHER_API_KEY=your_weather_api_key
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@example.com
MAIL_FROM_NAME="Laravel API"
```

### **4. Run Migrations & Seed Database**

```bash
php artisan migrate --seed
```

### **5. Start the Laravel Development Server**

```bash
php artisan serve
```

API will be accessible at: `http://127.0.0.1:8000/api`

---

## 🔐 Authentication

### **Register a New User**

```http
POST /api/register
```

**Body:**

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

### **Login a User**

```http
POST /api/login
```

**Body:**

```json
{
  "email": "john@example.com",
  "password": "password"
}
```

**Response:**

```json
{
  "token": "your_generated_token"
}
```

💡 Use this **Bearer Token** for authentication in all protected routes.

### **Logout a User**

```http
POST /api/logout
```

💡 Requires `Authorization: Bearer your_token`

---

## 📌 API Endpoints

### **Posts API**

| Method | Endpoint          | Description               | Auth Required  |
| ------ | ----------------- | ------------------------- | -------------- |
| GET    | `/api/posts`      | Get all posts (paginated) | ✅              |
| GET    | `/api/posts/{id}` | Get a single post by ID   | ✅              |
| POST   | `/api/posts`      | Create a new post         | ✅              |
| PATCH  | `/api/posts/{id}` | Update a post             | ✅ (Only owner) |
| DELETE | `/api/posts/{id}` | Delete a post             | ✅ (Only owner) |

### **Weather API**

| Method | Endpoint       | Description              |
| ------ | -------------- | ------------------------ |
| GET    | `/api/weather` | Get current weather data |

---

## ⏳ Queued Jobs

### **1. Sending Welcome Email** (when a user registers)

- This job runs in the background using Laravel queues.
- Start the queue worker:

```bash
php artisan queue:work
```

### **2. Weather Update Job** (Runs every hour)

- Automatically updates weather data every hour.
- Run Laravel Scheduler:

```bash
php artisan schedule:work
```

**Manually Dispatch Weather Job:**

```bash
php artisan tinker
>>> dispatch(new \App\Jobs\UpdateWeatherJob());
```

**Manually Dispatch Welcome Email Job:**

```bash
php artisan email:send-welcome 1
```

(Replace `1` with a valid user ID)

---

## 🧪 Running Tests

To run PHPUnit tests:

```bash
php artisan test
```

---

## 🚀 Deployment

1. **Set Up Database & Queues**
2. **Run Migrations**

```bash
php artisan migrate --force
```

3. **Start Queue Worker & Scheduler**

```bash
php artisan queue:work --daemon &
php artisan schedule:work &
```

4. **Configure a Cron Job (for scheduler)**

```bash
crontab -e
```

Add this line:

```bash
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```

---

## 📄 License

This project is licensed under the MIT License.

---

## 📞 Support

If you have any issues, feel free to open an issue or contribute via pull requests.

Happy Coding! 🚀

