# 🎬 VizzioStream

**VizzioStream** adalah website streaming anime modern yang dibangun menggunakan **Laravel 13**, **Tailwind CSS**, dan **Vite** dengan tampilan **Biru Neon + Dark Theme**.

Website ini menampilkan katalog anime yang terhubung dengan **wajik-anime-api** yang telah di-hosting di **Vercel** dan menggunakan provider **Oploverz** sebagai sumber data anime.

---

## ✨ Fitur Utama

* 🎞️ Hero Section modern dengan tema neon dark
* 🔍 Pencarian anime
* 📺 Daftar anime **ongoing** dan **completed**
* 🖼️ Grid katalog anime responsif
* 📱 Responsive untuk desktop dan mobile
* ⚡ Menggunakan **Vite** untuk asset bundling cepat
* 🎨 Dibangun dengan **Tailwind CSS**
* 🌐 Terhubung ke **wajik-anime-api (Vercel)**

---

## 🛠️ Tech Stack

* **Laravel 13**
* **PHP 8.3+**
* **Tailwind CSS**
* **Vite**
* **MySQL**
* **wajik-anime-api**
* **Vercel** (Anime API Hosting)

---

## 🚀 Instalasi Lokal

```bash
git clone https://github.com/username/VizzioStream.git
cd VizzioStream

composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

Buka di browser:

```text
http://127.0.0.1:8000
```

---

## ⚙️ Konfigurasi Anime API

Tambahkan pada file **.env**:

```env
ANIME_API_OPLOVERZ_BASE_URL=https://wajik-anime-api-red.vercel.app
ANIME_PROVIDER=oploverz
```

---

## 📦 Production Build

```bash
npm run build
```

Asset hasil build akan tersedia di folder:

```text
public/build
```

---

## 📸 Preview

VizzioStream menggunakan desain **Neon Blue + Dark** dengan fokus pada pengalaman menonton anime yang modern, ringan, dan responsif.

---

## 📄 License

Project ini dibuat untuk tujuan **pembelajaran, pengembangan portfolio, dan eksperimen web development** menggunakan Laravel dan Tailwind CSS.

---

## 👨‍💻 Author

**I Made Adi Wijaya**

* 🌐 GitHub: **Adiwijaya11**
* 🎓 Informatics Student at **INSTIKI**
