# ANN Studio

Tienda catálogo en PHP para ropa, lociones, carteras, gafas y accesorios. El cierre de compra genera un mensaje ordenado para WhatsApp al `+504 8916-1389`; no procesa pagos.

## Funciones

- Catálogo responsive, búsqueda, categorías y ordenamiento.
- Variantes de color/talla y código único por producto.
- Bolsa persistente en el navegador.
- Pedido detallado por WhatsApp con código, variante, talla, cantidad y total estimado.
- Panel en `/admin.php` con métricas de vistas, visitantes, artículos agregados, pedidos iniciados, valor potencial y rankings.
- Edición y creación de productos desde el panel.

## Primer acceso administrativo

Abre `/admin.php` después del despliegue y crea una contraseña de al menos 10 caracteres. No existe una contraseña predeterminada. El archivo `data/.htaccess` bloquea el acceso web directo a los datos.

## Requisitos y despliegue

- PHP 8.1 o superior con permisos de escritura en `data/`.
- Apache o LiteSpeed con soporte de `.htaccess`.
- Publicar todo el contenido de esta carpeta en la raíz web.

Los productos incluidos son demostrativos y se pueden reemplazar desde el panel. Para producción conviene cargar fotos y datos definitivos de cada producto.

## Vista local y túnel de VS Code

Inicia el servidor local con el router de seguridad incluido:

```powershell
& "C:\xampp\php\php.exe" -S 127.0.0.1:8765 router.php
```

El router bloquea el acceso público directo a `data/`, `includes/`, archivos Markdown, archivos ocultos y paquetes ZIP. Úsalo especialmente cuando la visibilidad del puerto de VS Code esté configurada como **Public**.
