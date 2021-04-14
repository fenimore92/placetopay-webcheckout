# Prueba desarrollador PHP

Una tienda muy básica, donde un cliente puede comprar un solo producto con un valor fijo, el cliente necesita únicamente proporcionar su nombre, dirección de correo electrónico y su número de celular para efectuar la compra. Una vez un cliente procede a la compra de su producto, como es debido, su sistema debe saber que se creó una nueva orden de pedido, asignarle su código único para identificarla y saber si esta se encuentra pendiente de pago o si ya se ha realizado un pago para poder “despacharla”.

La pasarela de pago a integrar es a través de **PlacetoPay** para el procesamiento de la transacción usando **Web Checkout** para que se puedan usar todos los medios de pago con una sola integración.

## Instalación

 1. Clonar el repositorio
 2. Configurar credenciales estos se encuentran en el archivo `.env`
	 1. Configurar las credenciales para la base de datos
	 2. Configurar las credenciales de la pasarela de pago **PlacetoPay**
		 1. `PLACETOPAY_LOGIN`
		 2. `PLACETOPAY_TRANKEY`
		 3. `PLACETOPAY_URL`
	 3. Configurar los parametros para la tienda
		 1. `SHOP_PRODUCT_PRICE` : Valor del producto entero positivo
		 2. `SHOP_PRODUCT_CURRENCY` : Moneda valores posibles COP o USD
 3. Instalar la paqueteria con el siguiente comando `php composer.phar install`
 4. Crear las tablas de la base de datos con el siguiente comando `php artisan migrate`

## Documentacion APIS

La documentacion del api se encuentra en https://documenter.getpostman.com/view/511623/TzJpgysQ

## Recomendaciones

Se debe tener instalado el modulo de SOAP para php