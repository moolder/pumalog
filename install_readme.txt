

TODO vor ver�ffentlichung:
icons_all: Was sind das f�r welche? lizenz?
player_flv_maxi.swf - wo kommt das her? lizenz?
test-dateien in unterordner test/ legen

TODO Known bugs
timestamp mit 1en: 0 zu viel wird hinzugef�gt

TODO Verbesserungspotenzial
function_permissions.php per config sollte ohne Benutzer nur Ansicht gefordert werden - auch login daf�r anpassen
function_permissions.php aktuell nur whitelist, auch blacklist erm�glichen
function_permissions.php permissions hartcodiert!
header-Auswahl f�r alle monate ab bild vor n�chstem bild


Installationsanleitung:
inhalt von . als Ordner unter htdocs ablegen
login/data/rndfile.txt anlegen und f�r www-data lese- und schreibbar machen
login/.htaccess anpassen (passwortfile f�r die benutzer anlegen
verzeichnisse anlegen, f�r www-data lese- und schreibbar machen, login/.htaccess dorthin kopieren:
incoming/
deleted/ 
content/ 
upload/ 
imgtemp/
Datei $ignorelist (content/ignorelist.txt) anlegen und f�r www-data lese- und schreibbar machen

f�r Mailparser:
config.php: $mailpath setzen, f�r PHP zugreifbar machen (in php.ini - details?)

Tipps:
Video hinzuf�gen als *.flv (f�r PC) und *.mp4 (f�r iPhone) - Upload manuell in content/ - oder?