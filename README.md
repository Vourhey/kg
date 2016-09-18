# KinopoiskGrabber

### Installation:
Copy the folder with the app to your server (kg/ for example), create db and open http://yoururl/kg/install.php Enter your server name, user name, password, db name script creates 3 databases: filmlist, watched, temp. After installation you can remove install.php, but don't touch dbconnect.php

### Run

Open http://yoururl/kg/ and start adding films to your list. You can import\export lists, but for now there's no easy way to do it. So you should open kg/csv.php with parameters such as method (import or export), table and file name. The script saves exported file on server in the same folder

Later I'll add here more information, it's only the beginning