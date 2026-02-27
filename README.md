# Contact Form API — Backend

REST API para gestión de contactos construida con **CodeIgniter 4** y **MySQL**.

## Tecnologías

- PHP 8.2+
- CodeIgniter 4.7
- MySQL 8+
- Composer

---

## Requisitos previos

Asegúrate de tener instalado en tu máquina:

- [PHP 8.2+](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/)
- [MySQL 8+](https://dev.mysql.com/downloads/)

---

## Instalación

### 1. Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd backend
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar variables de entorno

Copia el archivo de ejemplo y edítalo con tus datos:

```bash
cp env .env
```

Abre `.env` y configura la base de datos:

```env
CI_ENVIRONMENT = development

database.default.hostname = 127.0.0.1
database.default.database = contact_form_db
database.default.username = tu_usuario
database.default.password = tu_contraseña
database.default.DBDriver = MySQLi
database.default.port     = 3306
```

### 4. Crear la base de datos

Entra a MySQL y ejecuta:

```sql
CREATE DATABASE contact_form_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

Luego crea la tabla `contacts`:

```sql
USE contact_form_db;

CREATE TABLE contacts (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(150)  NOT NULL,
    message    TEXT          NOT NULL,
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);
```

### 5. Levantar el servidor de desarrollo

```bash
php spark serve
```

La API quedará disponible en: `http://localhost:8080`

---

## Endpoints

| Método | Ruta                    | Descripción              |
|--------|-------------------------|--------------------------|
| GET    | `/api/contacts`         | Listar todos los contactos 
| GET    | `/api/contacts/{id}`    | Obtener un contacto      |
| POST   | `/api/contacts`         | Crear un contacto        |
| PUT    | `/api/contacts/{id}`    | Actualizar un contacto   |
| DELETE | `/api/contacts/{id}`    | Eliminar un contacto     |

## Estructura del proyecto

```
backend/
├── app/
│   ├── Config/
│   │   ├── Cors.php          # Orígenes permitidos (CORS)
│   │   ├── Database.php      # Configuración de la BD (sin credenciales)
│   │   ├── Filters.php       # Registro de filtros globales
│   │   └── Routes.php        # Definición de rutas de la API
│   ├── Controllers/
│   │   └── ContactController.php  # Lógica de los endpoints CRUD
│   ├── Database/
│   │   ├── Migrations/       # Migraciones de la BD
│   │   └── Seeds/            # Datos de prueba
│   └── Models/
│       └── ContactModel.php  # Modelo de la tabla contacts
├── public/
│   └── index.php             # Punto de entrada de la aplicación
├── .env                      # Variables de entorno
├── env                       # Plantilla de ejemplo para el .env
├── composer.json             # Dependencias PHP
└── spark                     # CLI de CodeIgniter
```

---

## CORS

Si el frontend corre en un puerto distinto al `5173`, agrégalo en `app/Config/Cors.php`:

```php
'allowedOrigins' => ['http://localhost:5173', 'http://localhost:5174'],
```

---

## Notas

- El archivo `.env` está en `.gitignore` y **nunca** debe subirse al repositorio.
- Usar `127.0.0.1` en vez de `localhost` evita problemas de conexión por socket en MySQL.
