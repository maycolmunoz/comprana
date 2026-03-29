# Guía de Instalación y Desarrollo 📥

Se recomienda el uso de **Laravel Sail** (Docker) para un entorno de desarrollo consistente.

## Requisitos Previos
*   Docker & Docker Compose
*   Node.js & NPM (para compilación de assets si no usas Sail)

---

## Pasos de Instalación (Sail)

1.  **Clonar el repositorio**:
    ```bash
    git clone https://github.com/maycolmunoz/comprana.git
    cd comprana
    ```

2.  **Preparar el entorno**:
    ```bash
    cp .env.example .env
    ```

3.  **Instalar dependencias de PHP**:
    ```bash
    composer install
    ```

4.  **Iniciar servicios con Sail**:
    ```bash
    ./vendor/bin/sail up -d
    ```

5.  **Instalación Automática de la App**:
    Este comando genera la key, ejecuta migraciones, seeders, vincula el storage, genera roles/permisos y permite crear el primer administrador:
    ```bash
    ./vendor/bin/sail artisan app:install
    ```

6.  **Compilar Assets**:
    ```bash
    ./vendor/bin/sail npm install
    ./vendor/bin/sail npm run build
    ```

---

## 🔑 Variables de Entorno

El proyecto requiere configurar las siguientes variables en el archivo `.env`:

| Variable | Propósito |
| :--- | :--- |
| `DB_*` | Configuración de la base de datos (MySQL/MariaDB). |
| `MAIL_*` | Configuración para el envío de correos (Mailpit en desarrollo con Sail). |
| `MP_PUBLIC_KEY` | Llave pública de tu aplicación en [Mercado Pago](https://www.mercadopago.com.mx/developers/es/docs/sdks-library/server-side/php). |
| `MP_ACCESS_TOKEN` | Token de acceso de tu aplicación en Mercado Pago. |

---

## 💻 Desarrollo y Calidad

### Comandos Personalizados

*   `php artisan app:install`: Realiza todo el setup inicial interactivo (migraciones, seeders, roles, admin).
*   `php artisan clean-orders`: Elimina órdenes sin pago registradas hace más de un mes.

### 🧪 Pruebas (Testing)

El proyecto utiliza **PHPUnit** para garantizar la estabilidad del código. Para ejecutar la suite completa:

```bash
./vendor/bin/sail artisan test
```

### 🎨 Calidad de Código

Para mantener un estilo de código limpio siguiendo las convenciones de Laravel:

```bash
./vendor/bin/sail bin pint
```
