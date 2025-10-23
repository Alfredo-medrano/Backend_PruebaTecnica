<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 📘 Gestor de Tareas (To-Do App) - Backend API

Este repositorio contiene la implementación del **Backend (API RESTful)** para la prueba técnica de Full Stack Jr/Pasantía. El proyecto permite a los usuarios registrarse, autenticarse y gestionar de forma segura **solo sus tareas personales**.

## 💻 Stack Tecnológico 

El Backend está construido sobre **Laravel 12 (PHP)**.

| Criterio | Tecnología / Implementación | Buenas Prácticas |
| :--- | :--- | :--- |
| **Framework** | **Laravel 12 (PHP 8.2)** | Servidor API RESTful. |
| **Autenticación** | **JWT** (`tymondesigns/jwt-auth`) | Uso de Tokens Bearer para acceso protegido. |
| **Arquitectura** | **Service Layer** | Lógica de negocio separada en `AuthService` y `TaskService` para controladores limpios. |
| **Validación** | **Form Requests** | Uso de clases Request (ej. `RegisterRequest`) para validación limpia antes de la ejecución del controlador. |
| **Autorización** | **Relaciones Eloquent** | Lógica implementada para que el usuario solo acceda a **sus propias tareas**. |
| **Seguridad** | **JWT Blacklist** | El token se invalida explícitamente al cerrar sesión (`POST /logout`). |

---

## 🚀 Instalación y Pasos para Correr el Proyecto


### Requisitos Previos
* PHP (8.2 o superior)
* Composer
* MySQL Server 

### Pasos

1.  **Clonar y Dependencias**
    ```bash
    git clone [https://github.com/Alfredo-medrano/Backend_PruebaTecnica.git](https://github.com/Alfredo-medrano/Backend_PruebaTecnica.git)
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
    Esto creará las tablas `users` y `tasks` con las claves foráneas necesarias.
    ```bash
    php artisan migrate
    ```

5.  **Ejecutar Tests (Calidad Técnica)**
    Se incluye un conjunto completo de pruebas de integración (`AuthTest.php`, `TaskTest.php`).
    ```bash
    php artisan test
    ```

6.  **Iniciar el Servidor**
    ```bash
    php artisan serve
    ```
    El Backend estará disponible en `http://127.0.0.1:8000`.

---

## 🔑 Credenciales de Prueba 

Utilice estas credenciales o regístrese a través de `/api/register`.

| Campo | Valor |
| :--- | :--- |
| **Email** | `tester@gmail.com` |
| **Contraseña** | `password123!` |

*Nota: La contraseña incluye un símbolo para cumplir con las validaciones de seguridad.*

## 🔗 Endpoints de la API (Para Integración Frontend)

Todas las rutas están prefijadas con `/api/`. Las rutas protegidas requieren el **Token JWT** en la cabecera `Authorization: Bearer [TOKEN]`.

| Método | Endpoint | Requisito | Descripción |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/register` | Público | Crea un nuevo usuario. |
| `POST` | `/api/login` | Público | Autentica y devuelve el token JWT. |
| `POST` | `/api/logout` | Protegido | Invalida el token JWT. |
| `GET` | `/api/tasks` | Protegido | Lista tareas del usuario autenticado. |
| `POST` | `/api/tasks` | Protegido | Crea una nueva tarea. |
| `PUT/PATCH`| `/api/tasks/{id}` | Protegido | Edita una tarea del usuario. |
| `DELETE` | `/api/tasks/{id}` | Protegido | Elimina una tarea del usuario. |

---

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
