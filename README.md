# Comprana

Comprana es una aplicación web monolítica que permite comprar víveres desde casa y recibirlos en la puerta. Ofrece una amplia gama de productos, una interfaz intuitiva para buscar y comprar.

video: https://youtu.be/ZDsoQOYZTCI

## Capturas

| Inicio                              | Tienda                              | Carritos                                |
| ----------------------------------- | ----------------------------------- | --------------------------------------- |
| ![inicio](./_docs/imgs/inicio.webp) | ![tienda](./_docs/imgs/tienda.webp) | ![carritos](./_docs/imgs/carritos.webp) |

| Pedidos                               | Compra                              | Admin                             |
| ------------------------------------- | ----------------------------------- | --------------------------------- |
| ![pedidos](./_docs/imgs/pedidos.webp) | ![compra](./_docs/imgs/compra.webp) | ![admin](./_docs/imgs/admin.webp) |

## Tecnologías

| Paquete  | Versión |
| -------- | ------- |
| Laravel  | v12     |
| Filament | v4      |
| Livewire | v3      |

## Instalación

Así es como puede ejecutar el proyecto localmente:

1. Clonar repositorio

    ```sh
    git clone https://github.com/maycolmunoz/comprana.git
    ```

2. Ingresa al directorio raíz del proyecto

    ```sh
    cd comprana
    ```

3. Copie el archivo .env.example al archivo .env

    ```sh
    cp .env.example .env
    ```

4. Crea base de datos `comprana`

5. Instala dependencias PHP

    ```sh
    composer install
    ```

6. Instala app (Este comando crea la key, ejecuta migraciones, crea storage link, ejecuta seeders, genera roles-permisos y crea administrador)

    ```sh
    php artisan app:install
    ```

7. Instala dependencias front-end
    ```sh
    npm install && npm run build
    ```

## DB

![DB](./_docs/imgs/compranaDB.webp)
