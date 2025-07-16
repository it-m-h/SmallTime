# Small - Time

Die kleine Zeiterfassung für Privatpersonen und kleine Firmen.
Infos zu Installation und Bedienung: [http://www.small.li/](http://www.small.li/)

## Server - Voraussetzungen

- PHP Version 8 wird nun unterstützt.
- Webserver selber installiert? Dann php-xml und php-zip nicht vergessen!

## Administrator-Zugang

- Benutzername: **admin**
- Passwort: **1234** (nach Deployment unbedingt ändern!)

## Dateien im Root - Verzeichnis:

- **index.php** (Standard - Datei für Ihre Webseite -> **nicht löschen**)
- **admin.php** (Standard - Datei für den Admin - Bereich -> **nicht löschen**)
- **download.php** (wird für den Download der Dateien benötigt -> **nicht löschen**)
- **android.php** (wird benötigt, wenn die APP verwendet wird - wird nicht weiter gepflegt und **kann gelöscht werden**)
- **idtime.php** (wird benötigt um mit RFID oder Qrcode zu stempeln -> **kann gelöscht werden**)
- **stempelterminal.php** (ist ein Beispiel, wie ein Terminal aufgebaut werden könnte -> **sollte gelöscht werden**, wenn diese nicht verwendet werden soll, weil dort keine Passwort abfragen vorhanden sind)

## Verzeichnis - Berechtigungen setzen zum Schreiben bei LINUX:

(inkl. Unterverzeichnisse)

- ./Data
- ./import
- ./debug
- ./include/Settings

## XAMMP / LAMP installieren

- Windows : XAMPP von (www.apachefriends.org)
- ZIP von GIT downloaden und ins `c:/xampp/htdocs` kopieren. (alles im `htdocs` wird nicht benötigt)
- `c:/xampp/xampp-control.exe` ausführen und Webserver starten
- Webseite aufrufen: (http://127.0.0.1) (die IP vom Rechner geht auch, sowie auch localhost)

## Update einer alten Version

- Alten Ordner umbenennen (z.B. `/time` -> `/time_old)`
- Neue Version installieren (mit altem Ordnernamen, z. B. `/time`) 
- Schreibrechte setzen
- Inhalte von `./include/Settings` der alten Version übernehmen
- Inhalte von `./Data` komplett übernehmen

## UBUNTU - Installation - TIPPS

### Installation

- [Apache 2.4 installieren](https://wiki.ubuntuusers.de/Apache_2.4/)
- [PHP installieren](https://wiki.ubuntuusers.de/PHP/)
- [PHP 8.3 auf Ubuntu 22.04 installieren](https://www.erikdonner.dev/2023/12/29/php-8-3-auf-ubuntu-22-04-installieren/)
- [PHP-ZIP Extension installieren](https://www.itsolutionstuff.com/post/ubuntu-php-zip-extension-install-commands-exampleexample.html?utm_content=cmp-true)
- [PHP iconv-Funktion](https://www.php.net/manual/en/function.iconv.php)
- [php-xml installieren (Debian/Ubuntu)](https://www.php.net/manual/de/install.unix.debian.php)

### Berechtigungen

```bash
sudo chown www-data /var/www/html -R
sudo chgrp www-data /var/www/html -R
sudo chmod 750 /var/www/html/Data -R
sudo chmod 750 /var/www/html/import -R
sudo chmod 750 /var/www/html/debug -R
sudo chmod 750 /var/www/html/include/Settings -R
sudo apt install php-xml php-zip
sudo service apache2 restart
```

## Docker Installation

- `make build` → baut ein lokales Docker-Image (Docker und Docker Compose müssen installiert sein)
- `make up` → startet den Container unter http://localhost:8082/
- `make down` → stoppt den Container

Hinweis:
Falls die App nicht startet, könnten die Docker-Volumes `smalltime-data` und `smalltime-settings` nicht dem Benutzer `www-data` gehören. (Hinweis: mit chown & chgrp ändern)

### Docker Container mit Windows ohne make 
Voraussetzung: Docker Desktop ist installiert und gestartet
In der Konsole (CMD) zum Verzeichnis wechseln in dem SmallTime liegt

```bash
docker compose build
docker compose up
```

## DEBUG / Fehlermeldungen aktivieren:

Alle Meldungen können angezeigt werden, wenn DEBUG auf **true** gestellt wird.

- in der `index.php` - Zeile:34 = `define('DEBUG', **false**);`
- in der `admin.php` - Zeile:34 = `define('DEBUG', **false**);`
