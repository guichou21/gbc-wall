README  -  gbc-wall
====================

A website to provide an homepage and a wall of item (picture and video)
The readme describe the installation.

Activate php module
--------------------
php_intl

Get Symfony vendors 
======================
php composer.phar install

Define the parameters 
--------------------
* `app/config/parameters.yml`
 * the database accesss
 * the webSite name, description and Version
  * webSiteName
  * webSiteVersion
  * webSiteDescription

* `app/config/config_dev.yml`
 * swiftmailer
  
* `src/gbcreation/HomeBundle/resource/config/parameters.yml`
 * Home param Bundle
 * Contact infos
 * rss infos
 * external portfolio  url

If needed : add prefix to table (DO not forget to modify sql script.....)
* `src/gbcreation/WallBundle/resource/config/services.yml`
 * gbcreation.db.table_prefix: prefix_

* `src/gbcreation/UserBundle/resource/config/services.yml`
 * gbcreation.db.table_prefix: prefix_

Folder Web
------------
* Create folder `images/wall` (used to save the uploaded picture) 
* Change the picture in `images/slider`  (home slider picture)

WebSite stats (js)
--------------------
* `web/resources/js`
Paste google analytics script in : `sy2-wall.js`

Copy public files from the bundle web/public to web/public
-----------------------------------------------------------
    php app/console assets:install web --env=prod
    php app/console assets:install web

Create user to access admin panel
---------------------------------
    php app/console fos:user:create testuser test@example.com p@ssword
    php app/console fos:user:activate testuser
    php app/console fos:user:promote testuser ROLE_ADMIN
    or create with: --super-admin

Admin
---------
Connect with admin/adminpass then create a new admin user and delete this one

Start the example 
------------------
Use the sql script to integrate some picture and the admin user.
Deploy from the zip archive the pictures in the right folder : `images/wall`
