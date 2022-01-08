Consumo de API Spotify con Symfony
==================================

Se lleva a cabo la prueba técnica de [Symfony][2], donde se pide crear un módulo para consumir el API de [Spotify][1] y 
poder mostrar los últimos lanzamientos y de allí ver información de artistas y álbumes.

Instalación
-----------
* Clone el proyecto desde el repositorio git
```bash
$ git clone sebaris/spotify-bits-americas
```
* Instale las dependencias del proyecto
```bash
$ composer install
```
* Instale las dependencias para bootstrap
```bash
$ npm install
```
* Corra el servidor **Symfony**
```bash
$ symfony serve
```
Ingresando entonces a la dirección local que nos arroja la consola, podemos ver el inicio de la solución al problema.

1. Nos carga por defecto la página donde se observa los últimos 12 lanzamientos nuevos en Colombia.
2. Ingresando en los nombres de los artistas, nos lleva a una página para observar los datos del artista y sus álbumes.

Solución
--------
El proyecto consta de:
1. Un controlador llamado src/Controller/SpotifyController.php, donde se tiene las dos acciones a renderizar, el home y el de los artistas
2. Consta de dos clases Service
   1. /src/Service/AuthenticationService.php, donde se hace la validación del token de seguridad, revisando la vigencia del mismo y
   haciendo el llamado nuevamente al servicio del API, en caso de que se tenga que renovar por tiempo de expiración
   2. /src/Service/SpotifyService.php, esta es la clase donde se consulta la información que se requiera desde el controlador, consta
   de funciones para traer los nuevos lanzamientos, la información de artistas y sus álbumes y la información de las
   canciones asociadas a estos. Es acá entonces que se hace toda la lógica de acceso a los diferentes endpoints del API
3. Se desarrollo un extensión de Twig, la cual nos permite en el momento del renderizado de la vista de artistas, consultar
la primera canción de un álbum, respetando el acceso a la información desde el servicio anteriormente mencionado, esta
extensión se llama /src/Twig/SpotifyExtension.php. Se desacopla la definición (archivo anterior) y la implementación en
/src/Twig/SpotifyRuntime.php con el fin de mejorar el rendimiento puesto que es una extensión que inyecta el servicio que
accede al API.
4. Se realizó la carga de bootstrap y hojas de estilo mediante webpack-encore-bundle.

[1]: https://developer.spotify.com/documentation/web-api/
[2]: https://symfony.com/