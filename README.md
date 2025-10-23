<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 📘 Gestor de Tareas (To-Do App) - Backend API

Este repositorio contiene la implementación del **Backend (API RESTful)** para la prueba técnica de Full Stack Jr/Pasantía. El proyecto permite a los usuarios registrarse, autenticarse y gestionar de forma segura **solo sus tareas personales**.

## 💻 Stack Tecnológico y Criterios Cumplidos

El Backend está construido sobre el siguiente stack tecnológico, enfocándose en los criterios de evaluación:

| Criterio | Tecnología / Implementación | Notas |
| :--- | :--- | :--- |
| **Framework** | **Laravel 12 (PHP)** | Servidor API RESTful. |
| **Autenticación** | **JWT** (`tymondesigns/jwt-auth`) | Uso de Tokens Bearer para acceso protegido. |
| **Base de Datos** | **MySQL** | Base de datos relacional para persistencia. |
| **Buenas Prácticas** | **Request Classes** | Validación de datos separada y limpia (ej. email único, contraseñas). |
| **Autorización** | **Relaciones Eloquent** | [cite_start]Lógica implementada para que el usuario solo acceda a **sus propias tareas** (Ver solo sus propias tareas [cite: 9]). |
| **Seguridad** | **Mass Assignment Prevention** | Uso de `$fillable` en Modelos. |

---

## 🚀 Instalación y Pasos para Correr el Proyecto

[cite_start]Esta sección cumple con el requisito de [Incluir un archivo README.md con instrucciones para levantar el backend. [cite: 25]]

### Requisitos Previos
* PHP (8.2 o superior)
* Composer
* MySQL Server

### Pasos

1.  **Clonar y Dependencias**
    ```bash
    git clone https://github.com/Alfredo-medrano/Backend_PruebaTecnica.git
    cd Backend_PruebaTecnica
    npm install & npm run build
    ```

2.  **Configuración de Entorno y Claves**
    ```bash
    cp .env.example .env
    php artisan key:generate
    php artisan jwt:secret
    ```

3.  **Configuración de Base de Datos**
    Edite el archivo `.env` con sus credenciales de MySQL y **cree la base de datos** antes de migrar.

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=PruebaTecnica 
    DB_USERNAME=root
    DB_PASSWORD=root
    ```

4.  **Ejecutar Migraciones**
    [cite_start]Esto creará las tablas `users` [cite: 20] [cite_start]y `tasks` [cite: 21] con las claves foráneas necesarias.
    ```bash
    php artisan migrate
    ```

5.  **Iniciar el Servidor**
    ```bash
    php artisan serve
    ```
    El Backend estará disponible en `http://127.0.0.1:8000`.

---

## 🔑 Credenciales de Prueba (Para Evaluación)

Utilice estas credenciales de prueba o regístrese a través de `/api/register`.

| Campo | Valor |
| :--- | :--- |
| **Email** | `tester@gmail.com` |
| **Contraseña** | `password123` |

## 🔗 Endpoints de la API (Para Integración Frontend)

Todos los *endpoints* están prefijados con `/api/`.

### 1. Autenticación (Rutas Abiertas)

| Método | Endpoint | Requisito | Descripción |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/register` | No | [cite_start]Crea un nuevo usuario. [cite: 13] |
| `POST` | `/api/login` | No | [cite_start]Autentica y devuelve el token JWT. [cite: 14] |

### 2. Tareas (Rutas Protegidas)

Estas rutas requieren el **Token JWT** en la cabecera `Authorization: Bearer [TOKEN]`.

| Método | Endpoint | Requisito | Descripción |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/tasks` | Sí | [cite_start]Listar tareas del usuario autenticado. [cite: 15] |
| `POST` | `/api/tasks` | Sí | [cite_start]Crear una nueva tarea. [cite: 16] |
| `PUT` | `/api/tasks/{id}` | Sí | [cite_start]Editar tarea. [cite: 17] [cite_start]Autorización para **solo sus propias tareas**[cite: 9]. |
| `DELETE` | `/api/tasks/{id}` | Sí | [cite_start]Eliminar tarea. [cite: 18] [cite_start]Autorización para **solo sus propias tareas**[cite: 9]. |

---

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
