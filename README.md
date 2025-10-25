# 🧩 Talenavi Technical Test – Backend Developer

Proyek ini merupakan proyek API hasil pengerjaan **Technical Test Backend Developer** dari **Talenavi**.

Dibangun menggunakan **Laravel**.

## 🧾 Dokumentasi & Demo

📘 **Postman Collection:** [Link Postman Collection](https://www.postman.com/descent-module-astronomer-14693525/workspace/workspace-technical-test-talenavi/collection/35181536-0e8769d0-12c3-43c2-ae3f-6e6302dca590?action=share&creator=35181536)

🎥 **Video Presentasi:** [Link GDrive](https://drive.google.com/file/d/1_1HHzsvqnh-vhVF4to8XUsQSlffQQHuE/view?usp=sharing)

## 🚀 Tech Stack
- **Framework:** Laravel 12
- **Database:** MySQL
- **Library:** Maatwebsite Excel (untuk export data ke Excel)  
- **Testing Tool:** Postman

## ✅ Requirements

| No | Requirement | Status |
|----|--------------|:------:|
| 1 | **API Create Todo** – Menambahkan data Todo baru ke database | ✅ |
| 2 | **API Get Todo (Excel Report)** – Menghasilkan file Excel dengan data Todo dan ringkasan otomatis | ✅ |
| 3 | **API Get Todo (Chart Data)** – Menyediakan data terformat untuk kebutuhan visualisasi chart | ✅ |

## 📦 Pengujian Endpoints

### 1️⃣ Create Todo  
**Method:** `POST /api/createTodo`  

**Description:** Membuat data Todo baru. 

**Body Example (JSON):**
```json
{
  "title": "Complete Technical Test",
  "assignee": "Alvin",
  "due_date": "2025-10-30",
  "time_tracked": 90,
  "status": "in_progress",
  "priority": "high"
}
````

### 2️⃣ Get Todos (Excel Export)

**Method:** `GET /api/excel`

**Description:**
Menghasilkan file **Excel (.xlsx)** yang berisi daftar Todo, dilengkapi dengan:

* Format kolom otomatis
* Header bergaya
* Baris *summary* berisi total Todo dan total waktu (*time tracked*)
* Filter dinamis (berdasarkan title, assignee, status, priority, due_date, dan time_tracked)

**Example Request:**

```
GET /api/excel?status=pending,in_progress&priority=high
```

**Filtering Support**

Parameter GET digunakan untuk mencantumkan filter.
Filtering yang di-support bisa dilihat pada tabel berikut:

| Field          | Format Contoh                     | Keterangan                         |
| -------------- |-----------------------------------| ---------------------------------- |
| `title`        | `title=Meeting`                   | Pencarian sebagian (partial match) |
| `assignee`     | `assignee=John,Doe`               | Beberapa nama dipisah koma         |
| `due_date`     | `start=2025-10-01&end=2025-10-31` | Rentang tanggal                    |
| `time_tracked` | `min=30&max=120`                  | Rentang angka                      |
| `status`       | `status=pending,in_progress`      | Multi-value                        |
| `priority`     | `priority=low,high`               | Multi-value                        |

### 3️⃣ Get Todos (Chart Data)

**Method:** `GET /api/chart`

**Description:**
Mendapatkan data Todo yang telah dirangkum sebagai bahan visualisasi chart.

**Example Request:**

```
GET /api/chart?type=status
```

**Example Response:**

```json
{
  "status_summary": {
    "pending": 5,
    "in_progress": 3,
    "completed": 2
  },
  "priority_summary": {
    "low": 1,
    "medium": 6,
    "high": 3
  }
}
```

## ⚙️ Cara Menjalankan Proyek

```bash
# Clone repository
git clone https://github.com/adotrdot/Todo-API.git
cd talenavi-backend-test

# Install dependencies
composer install

# Copy environment config
cp .env.example .env

# Generate app key
php artisan key:generate

# Setup database di .env, lalu jalankan migrasi
php artisan migrate

# Jalankan server lokal
php artisan serve
```

Akses API di:
👉 `http://localhost:8000/api/`

## 👨‍💻 Author

**Alvin Aryanta Suwardono** ([LinkedIn](https://www.linkedin.com/in/alvinaryanta) · [GitHub](https://github.com/alvinaryanta))
