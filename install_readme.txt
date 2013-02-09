About pumalog:

Pumalog is a photolog - here you can easily share your favorite photos with your family and friends - on your own server.

Features
- saves the uploaded pictures without quality loss. 
- orders pictures into entries with a headline and optional text
- uses exif rotation info to rotate pictures
- manual rotation
- one click picture enhancement using filters
- ...

Installation instructions:

(the perl script install.pl does this up to a point - see below - for you on linux systems)
put the pumalog files in a folder inside the serverroot (i.e. "/var/www/pumalog/") and make them owned by webserver user (www-data):
inside this folder, create the following data files and make them read-writable for and owned by webserver user (www-data):
login/data/rndfile.txt 
content/ignorelist.txt

inside this folder, create the following data folders and make them read-write-executable for and owned by webserver user (www-data):
incoming/
deleted/ 
content/ 
upload/ 
imgtemp/

(install.pl ends here)

configure the users and passwordfile in login/.htaccess
copy the login/.htaccess file to all data folders you created above
create the passwordfile and the users in it by using htpasswd
configure the users and their permissions in function_permissions as follows:

each user has one line inside 
function getallusers(){
which looks like this:
	$users[] = "<username>";

the right to edit is given by a line
$userinfo["canedit"]["<username>"] = true;
otherwise, a user can only view the contents of the pumalog

the right to see all entries is given by a line
$userinfo["cansee"]["<username>"] = true;

otherwise, a user can see entries
- that have no special visibility flags
- that he may see according to the visibility flags


If you want to use the mailparser:
inside config.php, set $mailpath to the path where the mail files can be found. 
move the contents of the script/ folder to /root/script


Tipps:
Pumalog supports jpg images of any size. Other image formats are not supported. The original images are stored in the content/ folder as they were uploaded. They are scaled, rotated, filtered and recompressed at display time.

Deleted content is not really deleted but moved to dhe deleted/ folder. If you accidentally deleted an entry, you can just manually move it back from the deleted/ folder into the content/ folder to restore it. To permanently delete content you have to manually delete it inside the deleted/ folder.

pumalog supports flv video for PC display and mp4 video for iphone display. Just create an entry and then move the video into the content/ folder, using the same naming schema as with images, but with the flv/mp4 filename extension.

you can change the header image on a per-month-basis: inside the headerimages/ folder, create a header_yyyy_mm.png file, where yyyy is the year and mm is the month (01-12). The image will be shown on the page for that month. 


Known bugs
changing a timestamp to "01" in any field results in errors. Workaround: remove the leading zero and recommit.

change requests
enable view only mode without password - needs explicit logon and logoff
function_permissions.php - load permissions from file
login/ and function_permissions.php - change the way login works so it is not depending on apache auth. build an admin mask for creating and removing users, handling permissions, setting initial passwords. create a password update mask for users.
function_permissions.php aktuell nur whitelist, auch blacklist ermöglichen



Attribution
icons: Iconset ist SweetiePlus by Sublink Interactive: http://sublink.ca/icons/sweetieplus
Header image by katsrcool on flickr: http://www.flickr.com/photos/katsrcool/8318578664/sizes/o/in/photostream/
flv-player: http://flv-player.net/players/maxi/documentation/

