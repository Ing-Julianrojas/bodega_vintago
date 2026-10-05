# Bodega Vintago Child

Tema hijo de [Astra](https://wpastra.com/) para la tienda WooCommerce
**Bodega Vintago** (https://bodega.vintago.com.co/): tecnología, hogar,
belleza y productos para mayoristas en Bogotá, con envíos a todo Colombia.

## Tecnologías

- WordPress + tema padre Astra (tema hijo)
- WooCommerce
- PHP (plantillas y `functions.php`)
- JavaScript vanilla + jQuery (carrito, catálogo y favoritos por AJAX)
- CSS propio con tema oscuro y variables CSS
- Google Fonts (Inter), Open Graph y JSON-LD para SEO

## Requisitos

- WordPress 6.x o superior
- Tema padre **Astra** instalado (no está incluido en este repositorio)
- Plugin **WooCommerce** activo
- PHP 7.4 o superior

## Instalación

1. Instala y deja activo el tema **Astra** desde *Apariencia > Temas*.
2. Descarga este repositorio como ZIP (*Code > Download ZIP*).
3. En WordPress: *Apariencia > Temas > Añadir nuevo > Subir tema* y sube el ZIP.
4. Activa **Vintago Bodega Child**.
5. Crea las páginas que usan plantillas propias (Acceder, Mayoristas,
   Mi cuenta, Carrito, Finalizar compra) y asígnales su plantilla.

## Estructura

| Archivo / carpeta | Función |
|---|---|
| `functions.php` | Estilos, SEO, seguridad básica, hooks de WooCommerce y AJAX |
| `front-page.php` | Página de inicio |
| `single-product.php`, `archive-product.php` | Producto y catálogo |
| `page-*.php` | Páginas con plantilla propia |
| `woocommerce/myaccount/` | Sobrescritura de plantillas de Mi cuenta |
| `style.css`, `vintago-extras.css`, `vintago-neon-additions.css` | Estilos |
| `assets/vintago-catalog.js` | JavaScript del catálogo y favoritos |

## Qué NO está en este repositorio

- El contenido de la tienda (productos, pedidos, clientes): vive en la base de datos de WordPress.
- Claves, credenciales y `wp-config.php`.
- Los snippets configurados en WPCode.

## Licencia

Código bajo **GPL-2.0-or-later** (ver [LICENSE](LICENSE)), igual que WordPress y Astra.
El nombre y el logo de Bodega Vintago, y las imágenes de marca, no se ceden
con la licencia del código.
