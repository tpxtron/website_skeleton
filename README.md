# Installation #

* run `composer install`
* create a MySQL database 
* import the dumped `install.sql` into that database
* add the database username and password to `backend/db.php`
* use [Compass](http://compass-style.org/) to compile SASS to CSS (or otherwise it'll look shitty :-))
* grep for "TODO" and fix whatever comes across your way :-)


# Translations #

For translations to work, you need to edit the .po-files in the according `locale` subdirectory. Afterwards, the .po-files need to be compiled to .mo-files by using the command `msgfmt messages.po -o messages.mo`.
