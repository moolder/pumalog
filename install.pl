@dirs = (
"incoming",
"deleted",
"content",
"upload",
"imgtemp"
);
# user:group of the webserver
$wwwuser = "www-data:www-data";
$dirpermissions = "u+rwx";
$filepermissions = "u+rw";
$rnddir = "login/data";
$rndfile = "login/data/rndfile.txt";
$ignorelist = "content/ignorelist.txt";

for ($i = 0; $i < scalar @dirs; $i++){
  print "Checking ".$dirs[$i]."...\n";
  if (! -e $dirs[$i] ){
    print " creating dir ".$dirs[$i]."... ";
    mkdir $dirs[$i];
    print "DONE.\n";
    print " changing owner to ".$wwwuser."... ";
    system("chown ".$wwwuser." ".$dirs[$i]);
    print "DONE.\n";
    print " changing permissions to ".$dirpermissions."... ";
    system("chmod ".$dirpermissions." ".$dirs[$i]);
    print "DONE.\n";
                
  } elsif (! -d $dirs[$i] ){
    print " WARNING: ".$dirs[$i]." exists but is not a dir. ignoring. \n";
  } else {
    print " dir ".$dirs[$i]." exists. ignoring.\n";
  }
  
}

print "Checking ".$rndfile."...\n";  
if (! -e $rnddir){
  mkdir $rnddir;
}
if (! -e $rndfile){
  system("touch ".$rndfile);
  print " changing owner to ".$wwwuser."... ";
  system("chown ".$wwwuser." ".$rndfile);
  print "DONE.\n";
  print " changing permissions to ".$filepermissions."... ";
  system("chmod ".$filepermissions." ".$rndfile);
  print "DONE.\n";                            
} else {
  print $rndfile." exists. ignoring.\n";
}

print "Checking ".$ignorelist."...\n";
if (! -e $ignorelist){
  system("touch ".$ignorelist);
  print " changing owner to ".$wwwuser."... ";
  system("chown ".$wwwuser." ".$ignorelist);
  print "DONE.\n";
  print " changing permissions to ".$filepermissions."... ";
  system("chmod ".$filepermissions." ".$ignorelist);
  print "DONE.\n";
} else {
  print $ignorelist." exists. ignoring.\n";
}

print "chown'ing all files in . to ".$wwwuser."...\n";                
system("chown ".$wwwuser." *");
system("chown ".$wwwuser." .");

print "DONE.\n";
                
print "\nFinished.\n";
  