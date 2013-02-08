

TODO vor veröffentlichung:
icons_all: Was sind das für welche? lizenz?
player_flv_maxi.swf - wo kommt das her? lizenz?
test-dateien in unterordner test/ legen

TODO Known bugs
timestamp mit 1en: 0 zu viel wird hinzugefügt

TODO Verbesserungspotenzial
function_permissions.php per config sollte ohne Benutzer nur Ansicht gefordert werden - auch login dafür anpassen
function_permissions.php aktuell nur whitelist, auch blacklist ermöglichen
function_permissions.php permissions hartcodiert!
header-Auswahl für alle monate ab bild vor nächstem bild


Installationsanleitung:
inhalt von . als Ordner unter htdocs ablegen
login/data/rndfile.txt anlegen und für www-data lese- und schreibbar machen
login/.htaccess anpassen (passwortfile für die benutzer anlegen
verzeichnisse anlegen, für www-data lese- und schreibbar machen, login/.htaccess dorthin kopieren:
incoming/
deleted/ 
content/ 
upload/ 
imgtemp/
Datei $ignorelist (content/ignorelist.txt) anlegen und für www-data lese- und schreibbar machen

für Mailparser:
config.php: $mailpath setzen, für PHP zugreifbar machen (in php.ini - details?)

Tipps:
Video hinzufügen als *.flv (für PC) und *.mp4 (für iPhone) - Upload manuell in content/ - oder?