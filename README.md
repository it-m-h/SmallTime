# SmallTime

> [!WARNING]
> **Dieses Projekt wird nicht mehr weiterentwickelt.**
>
> Der Nachfolger ist **[SmallTime 27](https://github.com/it-m-h/SmallTime-27)**. Er befindet sich aktuell im Beta-Stadium, Version 1.0 ist für 2027 geplant.
>
> Dieses Repository bleibt als Archiv verfügbar. Es werden **keine Bugfixes und keine Sicherheitsupdates** mehr veröffentlicht. Wer SmallTime weiterhin betreibt, sollte die Installation nicht öffentlich im Internet zugänglich machen.

Die kleine Zeiterfassung für Privatpersonen und kleine Firmen.

Infos zu Installation und Bedienung: [www.small.li](http://www.small.li/)

## Inhalt

- [Server-Voraussetzungen](#server-voraussetzungen)
- [Administrator-Zugang](#administrator-zugang)
- [Dateien im Root-Verzeichnis](#dateien-im-root-verzeichnis)
- [Schreibrechte (Linux)](#schreibrechte-linux)
- [Installation mit XAMPP (Windows)](#installation-mit-xampp-windows)
- [Installation unter Ubuntu](#installation-unter-ubuntu)
- [Installation mit Docker](#installation-mit-docker)
- [Update einer alten Version](#update-einer-alten-version)
- [Debug-Modus](#debug-modus)
- [Lizenz](#lizenz)

## Server-Voraussetzungen

- PHP 8 wird unterstützt.
- Die PHP-Erweiterungen `php-xml` und `php-zip` müssen installiert sein.

## Administrator-Zugang

| Benutzername | Passwort |
|--------------|----------|
| `admin`      | `1234`   |

> [!IMPORTANT]
> Das Passwort nach der Installation unbedingt ändern.

## Dateien im Root-Verzeichnis

| Datei                 | Zweck                                                        | Löschen?                  |
|-----------------------|--------------------------------------------------------------|---------------------------|
| `index.php`           | Startseite der Zeiterfassung                                 | **nicht löschen**         |
| `admin.php`           | Admin-Bereich                                                | **nicht löschen**         |
| `download.php`        | Download von Dateien                                         | **nicht löschen**         |
| `android.php`         | Schnittstelle für die Android-App (wird nicht mehr gepflegt) | kann gelöscht werden      |
| `idtime.php`          | Stempeln mit RFID oder QR-Code                               | kann gelöscht werden      |
| `stempelterminal.php` | Beispiel für ein Stempelterminal, **ohne Passwortabfrage**   | **sollte gelöscht werden**, wenn nicht benötigt |

## Schreibrechte (Linux)

Folgende Verzeichnisse (inkl. Unterverzeichnisse) benötigen Schreibrechte für den Webserver:

- `./Data`
- `./import`
- `./debug`
- `./include/Settings`

## Installation mit XAMPP (Windows)

1. XAMPP von [apachefriends.org](https://www.apachefriends.org/) installieren.
2. ZIP von GitHub herunterladen und den Inhalt nach `C:\xampp\htdocs` kopieren. Die bestehenden Dateien in `htdocs` werden nicht benötigt.
3. `C:\xampp\xampp-control.exe` starten und den Apache-Webserver starten.
4. Im Browser [http://127.0.0.1](http://127.0.0.1) aufrufen (alternativ `localhost` oder die IP-Adresse des Rechners).

## Installation unter Ubuntu

### Anleitungen

- [Apache 2.4 installieren](https://wiki.ubuntuusers.de/Apache_2.4/)
- [PHP installieren](https://wiki.ubuntuusers.de/PHP/)
- [PHP 8.3 auf Ubuntu 22.04 installieren](https://www.erikdonner.dev/2023/12/29/php-8-3-auf-ubuntu-22-04-installieren/)
- [PHP-ZIP-Erweiterung installieren](https://www.itsolutionstuff.com/post/ubuntu-php-zip-extension-install-commands-exampleexample.html)
- [PHP iconv-Funktion](https://www.php.net/manual/en/function.iconv.php)
- [php-xml installieren (Debian/Ubuntu)](https://www.php.net/manual/de/install.unix.debian.php)

### Berechtigungen setzen

```bash
sudo apt install php-xml php-zip
sudo chown www-data:www-data /var/www/html -R
sudo chmod 750 /var/www/html/Data -R
sudo chmod 750 /var/www/html/import -R
sudo chmod 750 /var/www/html/debug -R
sudo chmod 750 /var/www/html/include/Settings -R
sudo service apache2 restart
```

## Installation mit Docker

Voraussetzung: Docker und Docker Compose sind installiert.

### Mit `make`

| Befehl       | Beschreibung                                        |
|--------------|-----------------------------------------------------|
| `make build` | baut ein lokales Docker-Image                       |
| `make up`    | startet den Container unter http://localhost:8082/  |
| `make down`  | stoppt den Container                                |

### Unter Windows ohne `make`

Voraussetzung: Docker Desktop ist installiert und gestartet. In der Konsole in das Verzeichnis von SmallTime wechseln und ausführen:

```bash
docker compose build
docker compose up
```

> [!NOTE]
> Startet die App nicht, gehören die Docker-Volumes `smalltime-data` und `smalltime-settings` möglicherweise nicht dem Benutzer `www-data`. Die Rechte lassen sich mit `chown` anpassen.

## Update einer alten Version

1. Alten Ordner umbenennen (z. B. `/time` → `/time_old`).
2. Neue Version mit dem bisherigen Ordnernamen installieren (z. B. `/time`).
3. Schreibrechte setzen.
4. Inhalt von `./include/Settings` aus der alten Version übernehmen.
5. Inhalt von `./Data` vollständig übernehmen.

## Debug-Modus

Um alle Fehlermeldungen anzuzeigen, `DEBUG` auf `true` setzen. Im produktiven Betrieb sollte der Wert `false` sein.

- `index.php`, Zeile 34: `define('DEBUG', false);`
- `admin.php`, Zeile 34: `define('DEBUG', false);`

## Lizenz

[GNU Affero General Public License v3.0](LICENSE)
