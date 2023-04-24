# Php_Symfony-Tareas
Proyecto de software de tareas 

IMPORTANT: no importados los ficheros vendor, var, migrations... instalar Symfony para generarse automaticamente

# Instalacion de Symfony y creación de BD paso a paso:

## Crear el proyecto desde el CMD:
- Entrar na carpeta del proyecto
	cd C:/wamp64/www/Curso-PHP

- Generar el poryecto (nombre proyecto 'proyecto-symfony')

	composer create-project symfony/website-skeleton proyecto-symfony

## Generar Host Virtual
- En el archivo 'httpd-vhosts.conf' en la carpeta C:\wamp64\bin\apache\apache2.4.51\conf\extra añadir:

	<VirtualHost *:80>   
    		DocumentRoot "${INSTALL_DIR}/www/Curso-PHP/proyecto-symfony/public"
    		ServerName proyecto-symfony.com.devel

    		<Directory "${INSTALL_DIR}/www/Curso-PHP/proyecto-symfony/public">
        		Options Indexes FollowSymLinks     
        		AllowOverride All
        		Order Deny,Allow
        		Allow from all     
   	 	</Directory> 
	</VirtualHost>

- En el archivo 'host' en la carpeta C:\Windows\System32\drivers\etc añadir:

	127.0.0.1 proyecto-symfony.com.devel

- Reiniciar el servidor Web 

## Crear diagrama en DIA y Base de Datos con SQL 
![imagen](https://user-images.githubusercontent.com/124586059/234128705-490b0ec3-4b67-4ad3-aca9-79814bff2f53.png)


## Vincular BD con el proyecto 
- En el archivo .env

	DATABASE_URL="mysql://root:@127.0.0.1:3308/symfony_master"

- En el CMD vincular y crear Entidades

	php bin/console doctrine:mapping:import App\Entity annotation --path=src/Entity

- Crear los setters y getters en las entidades

	php bin/console make:entity --regenerate App
