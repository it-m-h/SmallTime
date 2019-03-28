# Small - Time
Die kleine Zeiterfassung für Privatpersonen und kleine Firmen.
Infos zu Installation und Bedienung: [http://www.small.li/](http://www.small.li/)
[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy)

## Administrator-Zugang
* Benutzername: <b>admin</b>
* Passwort: <b>1234</b> (Nach Deployment unmittelbar ändern!)

## Dateien im Root - Verzeichnis:

* <b>index.php</b> (Standard - Datei für Ihre Webseite -> darf nicht gelöscht werden)
* <b>admin.php</b> (Standard - Datei für den Admin - Bereich -> darf nicht gelöscht werden)
* <b>download.php</b> (wird für den Download der Dateien benötigt -> darf nicht gelöscht werden)
* <b>android.php</b> (wird benötigt, wenn die APP verwendet wird - wird nicht weiter gepflegt und <b>kann gelöscht werden</b>)
* <b>idtime.php</b> (wird benötigt um mit RFID oder Qrcode zu stempeln -> <b>kann gelöscht werden</b>)
* <b>stempelterminal.php</b> (ist ein Beispiel, wie ein Terminal aufgebaut werden könnte -> <b>sollte gelöscht werden</b>, wenn diese nicht verwendet werden soll, weil dort keine Passwort abfragen vorhanden sind)

## Verzeichnis - Berechtigungen setzen zum Schreiben bei LINUX: 
(inkl. Unterverzeichnisse)
* ./Data
* ./import
* ./debug
* ./include/Settings

## Update einer alten Version
* Ordner der alten Version umbenennen (z.B. /time -> /time_old)
* neue Version installieren (Ordner wie früher benennen /time)
* Schreibrechte auf Ordner setzen in der neuen Version
* ./include/Settings -> Dateien der alten Version in die neue kopieren
* ./Data -> alle Ordner und Dateien der alten Version in die neue kopieren