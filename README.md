# Small - Time

Die kleine Zeiterfassung für Privatpersonen und kleine Firmen.
Infos zu Installation und Bedienung: [http://www.small.li/](http://www.small.li/)

## Server - Voraussetzungen

- PHP Version 8 wird nun unterstützt.
- Webserver selber installiert? Extension php-xml & php-zip nicht vergessen zu installieren

## Administrator-Zugang

- Benutzername: <b>admin</b>
- Passwort: <b>1234</b> (Nach Deployment unmittelbar ändern!)

## Dateien im Root - Verzeichnis:

- <b>index.php</b> (Standard - Datei für Ihre Webseite -> darf nicht gelöscht werden)
- <b>admin.php</b> (Standard - Datei für den Admin - Bereich -> darf nicht gelöscht werden)
- <b>download.php</b> (wird für den Download der Dateien benötigt -> darf nicht gelöscht werden)
- <b>android.php</b> (wird benötigt, wenn die APP verwendet wird - wird nicht weiter gepflegt und <b>kann gelöscht werden</b>)
- <b>idtime.php</b> (wird benötigt um mit RFID oder Qrcode zu stempeln -> <b>kann gelöscht werden</b>)
- <b>stempelterminal.php</b> (ist ein Beispiel, wie ein Terminal aufgebaut werden könnte -> <b>sollte gelöscht werden</b>, wenn diese nicht verwendet werden soll, weil dort keine Passwort abfragen vorhanden sind)

## Verzeichnis - Berechtigungen setzen zum Schreiben bei LINUX:

(inkl. Unterverzeichnisse)

- ./Data
- ./import
- ./debug
- ./include/Settings

## Update einer alten Version

- Ordner der alten Version umbenennen (z.B. /time -> /time_old)
- neue Version installieren (Ordner wie früher benennen /time)
- Schreibrechte auf Ordner setzen in der neuen Version
- ./include/Settings -> Dateien der alten Version in die neue kopieren
- ./Data -> alle Ordner und Dateien der alten Version in die neue kopieren

## UBUNTU - Installation - TIPPS

### Installation

- [Ubuntu - Apache 2.4](https://wiki.ubuntuusers.de/Apache_2.4/)
- [Ubuntu - PHP Installation](https://wiki.ubuntuusers.de/PHP/)
- [PHP 8.3 auf Ubuntu 22.04 installieren](https://www.erikdonner.dev/2023/12/29/php-8-3-auf-ubuntu-22-04-installieren/)
- [Ubuntu PHP ZIP Extension](https://www.itsolutionstuff.com/post/ubuntu-php-zip-extension-install-commands-exampleexample.html?utm_content=cmp-true)

### Berechtigungen

- sudo chown www-data /var/www/html -R
- sudo chgrp www-data /var/www/html -R
- sudo chmod 750 /var/www/html/Data -R
- sudo chmod 750 /var/www/html/import -R
- sudo chmod 750 /var/www/html/debug -R
- sudo chmod 750 /var/www/html/include/Settings -R
- sudo apt install php-xml php-zip
- sudo service apache2 restart

## Docker Installation

mit dem Befehl `make build` wird ein Dockerimage lokal gebuilded. (es muss docker und docker compose installiert sein)

mit dem Befehl `make up` wird ein docker-compose gestartet und stellt smalltime per http://localhost:8082/ zur Verfügung.
mit dem Befehl `make down` wird das docker-compose wieder gestoppt.

Wenn die App nicht startet könnte der Benutzer und die Gruppe von den beiden Volumes smalltime-data und smalltime-settings nicht auf www-data eingerichtet sein.

## Error

Alle Meldungen können angezeigt werden, wenn DEBUG auf **true** gestellt wird.

- index.php - Zeile:34 = define('DEBUG', **false**);
- admin.php - Zeile:34 = define('DEBUG', **false**);
