gbc-wall
====================

A website to provide a homepage and a wall of item (photos and video)

Activate php module
====================
php_intl

Get Symfony vendors 
======================
php composer.phar install

Define the parameters 
======================
#app/config/parameters.yml
the database accesss
the webSite name and description
    webSiteName
    webSiteDescription

#src/gbcreation/HomeBundle/resource/config/parameters.yml
    Home param Bundle
    Contact infos
    rss infos
    external portfolio  url

Folder Web
===========
- To save the uploaded picture, create the folder images/wall
- Change the picture in images/slider

WebSite stats (js)
==================
#web/resources/js
Paste google analytics script in: sy2-wall.js

Copy public files from the bundle web/public to web/public
=======================================================
php app/console assets:install web --env=prod
php app/console assets:install web


Create user to access admin panel
===================================
php app/console fos:user:create testuser test@example.com p@ssword
php app/console fos:user:activate testuser
php app/console fos:user:promote testuser ROLE_ADMIN

or create with: --super-admin


Admin
=======
Connect with admin/adminpass then create a new admin user and delete this one


Put data
=========
user sql script and images in zip archive to initiate an example
