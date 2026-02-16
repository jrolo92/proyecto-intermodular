## 1. DESGLOSE DE COSTES
1. Modelo de Facturación: On-Demand (Pago por uso).

2. Justificación del Coste: Se ha presupuestado la instancia t3.micro por ser la opción más eficiente en relación rendimiento/precio para el stack LEMP (el cual es mas ligero y moderno). El coste de 1,60 USD (1,34 €) en almacenamiento corresponde a un volumen de 20GB gp3, que ofrece un equilibrio óptimo entre IOPS (velocidad de lectura/escritura) y coste mensual.

3. Comparativa: El coste anual de 110,28 USD (92,64€) es inferior al mantenimiento de un servidor físico propio (considerando electricidad y renovación de hardware), con la ventaja añadida de la alta disponibilidad de AWS.

## 2. STACK TECNOLÓGICO
1. Stack LNMP Operativo: Se ha instalado y vinculado con éxito Nginx como servidor frontal, MariaDB como motor de datos y PHP 8.2-FPM para el procesamiento de scripts.

2. Seguridad de Base de Datos (RA3.c): No se utiliza el usuario root para la aplicación. Se ha creado el usuario user_app con permisos restringidos exclusivamente a la base de datos traileros_db.

3. Configuración de Servicios: Se ha configurado el socket de Unix (/run/php-fpm/www.sock) para la comunicación entre Nginx y PHP, optimizando el rendimiento frente al uso de sockets TCP.

## 3. SEGURIDAD DE RED Y FIREWALL
1. Se ha configurado firewalld permitiendo los servicios esenciales (SSH, HTTP, HTTPS). Los servicios adicionales dhcpv6-client y mdns se mantienen activos por ser la configuración predeterminada de red de la instancia EC2 de AWS para garantizar la conectividad interna.

2. Implementación de HTTPS: Se ha implementado el cifrado de capa de transporte (TLS/SSL) mediante la generación de un certificado autofirmado utilizando la herramienta openssl. El servidor Web Nginx ha sido configurado para escuchar en el puerto 443, vinculando las llaves privada y pública alojadas de forma segura en /etc/nginx/ssl/. Se ha verificado la integridad del cifrado accediendo vía navegador, confirmando que la comunicación entre cliente y servidor está protegida.

3. Hardening del servicio SSH (RA3.e): Se ha securizado el acceso administrativo desactivando la autenticación por contraseña (PasswordAuthentication no) en el archivo sshd_config. El acceso queda restringido exclusivamente a usuarios con llaves criptográficas autorizadas, mitigando ataques de fuerza bruta.

## 4. DOCUMENTACIÓN AS-BUILT
1. Versiones del software:
- nginx version: nginx/1.28.1
- PHP 8.2.30 (cli)
- mariadb  Ver 15.1 Distrib 10.5.29-MariaDB

2. Ruta de los archivos de configuración modificados:
- Configuración Nginx: /etc/nginx/nginx.conf y /etc/nginx/conf.d/ssl.conf.
- Configuración PHP-FPM: /etc/nginx/default.d/php.conf.
- Directorio Web: /usr/share/nginx/html/.
- Certificados: /etc/nginx/ssl/

3. Puertos usados:
- 22 (SSH): Gestión remota cifrada.
- 80 (HTTP): Tráfico web estándar.
- 443 (HTTPS): Tráfico web cifrado con TLS.

## 6. GESTIÓN DE USUARIOS
- user: deployer 
- user: root 
- user: javi 
- BD: traileros_db