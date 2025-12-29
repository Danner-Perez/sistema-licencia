# 🚀 Sistema Web – Laravel

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FB503B?style=for-the-badge&logo=laravel&logoColor=white&labelColor=101010)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white&labelColor=101010)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=for-the-badge&logo=mysql&logoColor=white&labelColor=101010)](https://www.mysql.com/)

[![HTML](https://img.shields.io/badge/HTML5-orange?style=for-the-badge&logo=html5&logoColor=white&labelColor=101010)](https://developer.mozilla.org/es/docs/Web/HTML)
[![CSS](https://img.shields.io/badge/CSS3-blue?style=for-the-badge&logo=css3&logoColor=white&labelColor=101010)](https://developer.mozilla.org/es/docs/Web/CSS)
[![JavaScript](https://img.shields.io/badge/JavaScript-yellow?style=for-the-badge&logo=javascript&logoColor=white&labelColor=101010)](https://developer.mozilla.org/es/docs/Web/JavaScript)

## 📌 Descripción

Este proyecto es un sistema web administrativo desarrollado con Laravel.
Incluye:
- Gestión de usuarios y roles
- Módulos administrativos
- Exportación de datos a Excel
- Diseño responsive y limpio

---

## 🧰 Tecnologías Utilizadas

### Backend
- PHP 8.2+
- Laravel 12
- Migrations & Seeders

### Frontend
- Blade Templates
- JavaScript 
- HTML5 / CSS3
- Alpine.js 

### Exportación de Datos
- **Maatwebsite/Laravel-Excel**
- **PhpOffice/PhpSpreadsheet**

### Herramientas
- Composer
- NPM / Vite
- Git & GitHub

## 📋 Requisitos del Sistema

Antes de instalar, asegúrate de tener instalado:

- PHP >= 8.2
- Composer
- Node.js >= 18


Extensiones PHP requeridas:
- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Fileinfo

---



## ⚙️ Instalación

### 1️⃣ Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/tu-repositorio.git
cd tu-repositorio
````

---

### 2️⃣ Instalar dependencias

```bash
composer install
npm install
npm run build
```



---

### 3️⃣ Configurar archivo de entorno

Copiar el archivo de ejemplo y generar la clave de la aplicación:

```bash
cp .env.example .env
php artisan key:generate
```

> ⚠️ La configuración de base de datos se define localmente en el archivo `.env`.

---

### 4️⃣ Ejecutar migraciones y seeders

```bash
php artisan migrate --seed
```

---

### 5️⃣ Iniciar el servidor

```bash
php artisan serve
```

Acceder desde el navegador:

```
http://localhost:8000
```

---

### 👤 Usuario Administrador Inicial

```
Email: admin@sistema.com
Password: admin123
```

> ⚠️ Cambiar credenciales 

```

