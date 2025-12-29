
# 🚀 Sistema Web – Laravel

Sistema web administrativo desarrollado con **Laravel 12**, enfocado en **arquitectura limpia**, **buenas prácticas de ingeniería de software** y una **experiencia de usuario clara y eficiente**.

<p align="center">
  <a href="https://laravel.com">
    <img src="https://img.shields.io/badge/Laravel-12.x-FB503B?style=for-the-badge&logo=laravel&logoColor=white" />
  </a>
  <a href="https://www.php.net">
    <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  </a>
  <a href="https://www.mysql.com">
    <img src="https://img.shields.io/badge/MySQL-5.7+-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
  </a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/HTML5-orange?style=for-the-badge&logo=html5&logoColor=white" />
  <img src="https://img.shields.io/badge/CSS3-blue?style=for-the-badge&logo=css3&logoColor=white" />
  <img src="https://img.shields.io/badge/JavaScript-yellow?style=for-the-badge&logo=javascript&logoColor=white" />
</p>

---

## 📌 Descripción

Este proyecto es un **sistema web administrativo** desarrollado con Laravel, diseñado para ser:

- ✅ Escalable
- ✅ Seguro
- ✅ Mantenible
- ✅ Fácil de instalar

Ideal para entornos académicos, institucionales o como base para proyectos empresariales.

---

## ✨ Características Principales

- Gestión de usuarios y roles
- Módulos administrativos desacoplados
- Exportación de datos a Excel con formato profesional
- Diseño responsive (desktop y mobile)
- Código limpio y organizado

---

## 🧰 Stack Tecnológico

### Backend
- PHP 8.2+
- Laravel 12
- Eloquent ORM
- Migrations & Seeders

### Frontend
- Blade Templates
- JavaScript (ES6+)
- HTML5 / CSS3
- Alpine.js

### Exportación de Datos
- **Maatwebsite/Laravel-Excel**
- **PhpOffice/PhpSpreadsheet**

### DevOps / Tooling
- Composer
- NPM / Vite
- Git & GitHub

---

## ⚙️ Instalación Rápida

```bash
git clone https://github.com/Danner-Perez/sistema-licencia.git
cd sistema-licencia

composer install
npm install
npm run build

cp .env.example .env
php artisan key:generate

php artisan migrate --seed
php artisan serve
````

Accede desde:

```
http://localhost:8000
```

---

## 👤 Usuario Administrador Inicial

```
Email: admin@sistema.com
Password: admin123
```

> 🔐 Cambia estas credenciales después de la primera instalación.

---

## 📤 Exportación a Excel

El sistema incorpora exportaciones avanzadas a Excel, incluyendo:

* Encabezados personalizados
* Estilos (alineación, bordes, colores)
* Formato de fechas
* Eventos `AfterSheet`

Las dependencias se gestionan automáticamente con Composer.

---

## 🔐 Seguridad

* Protección CSRF
* Validaciones de formularios
* Variables sensibles gestionadas mediante `.env`
* `.env` excluido del repositorio

---



## 👨‍💻 Autor

**Danny**
Ingeniería de Software
Desarrollador Full Stack

---

## 📄 Licencia

Proyecto de uso privado / académico / institucional.
Todos los derechos reservados.

---

⭐ Si este proyecto te resulta útil, no olvides darle una estrella en GitHub.

