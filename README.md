# SmallTime wird eingestellt

**Projekt eingestellt** · Stand: 19. Oktober 2026

SmallTime 2027 kommt nicht auf den Markt. SmallTime PHP wird nicht mehr weiterentwickelt, nicht mehr verteilt und sollte nicht mehr betrieben werden.

> [!CAUTION]
> **Wenn Sie SmallTime PHP noch nutzen**
>
> Die PHP-Version entspricht nicht mehr den heutigen Anforderungen an die Sicherheit. Sie enthält Zeiten, Absenzen und Personaldaten Ihrer Mitarbeitenden. Bitte sichern Sie Ihre Daten und nehmen Sie die Installation vom Netz, sobald Sie umgestiegen sind. Wie das geht, steht [weiter unten](#was-sie-jetzt-tun-sollten).

## Warum dieser Schritt

SmallTime PHP ist über viele Jahre gewachsen. Der Aufbau stammt aus einer Zeit, in der Sicherheit im Web anders verstanden wurde. Die Schwachstellen lassen sich nicht mit einzelnen Korrekturen beheben, sondern nur mit einem Neubau. Darum wird die PHP-Version nicht mehr gepflegt und nicht mehr zum Download angeboten.

Der geplante Nachfolger SmallTime 2027 wird wegen der neuen EU-Regeln für Software nicht veröffentlicht. Die Gründe stehen auf [www.small.li](https://www.small.li/).

## Was Sie jetzt tun sollten

1. **Daten sichern**
   Kopieren Sie die Ordner `Data` und `include/Settings` vollständig auf ein separates Laufwerk. Darin liegen alle Zeiten, Absenzen, Einstellungen und die erstellten PDF-Monatsrapporte.
2. **Unterlagen aufbewahren**
   Arbeitszeitunterlagen müssen in der Schweiz mindestens fünf Jahre aufbewahrt werden (Art. 73 ArGV 1). Erstellen Sie für alle Mitarbeitenden die Monatsrapporte als PDF und legen Sie sie zusammen mit der Sicherung ab.
3. **Zugriff einschränken**
   Bis Sie umgestiegen sind: Betreiben Sie SmallTime nicht offen im Internet. Erlauben Sie den Zugriff nur noch aus Ihrem Firmennetz oder per VPN. Löschen Sie die Datei `stempelterminal.php`, falls sie noch vorhanden ist, denn sie fragt kein Passwort ab. Ändern Sie das Admin-Passwort, falls es noch das Startpasswort ist.
4. **Auf eine gepflegte Zeiterfassung wechseln**
   Wählen Sie ein Produkt, dessen Anbieter Sicherheitsupdates garantiert. Die Monatsrapporte und Saldi aus SmallTime dienen als Eröffnungswerte in der neuen Lösung.
5. **SmallTime entfernen**
   Wenn die neue Lösung läuft und die Sicherung geprüft ist, löschen Sie SmallTime vom Webserver. Die gesicherten Daten bewahren Sie offline auf.

## So geht es weiter

| | |
|---|---|
| **Ab sofort** | Keine Downloads, keine neuen Versionen und keine Weiterentwicklung mehr. |
| **Quellcode** | Dieses Repository ist archiviert und nur noch lesbar. Der Code wurde entfernt, frühere Versionen sind nur noch in der Git-History nachvollziehbar und sollten nicht mehr eingesetzt werden. SmallTime PHP bleibt unter der Lizenz [AGPL-3.0](LICENSE). Kopien, die andere davon weiterführen, werden nicht von mir gepflegt oder geprüft. |
| **Fragen** | Wer Fragen zum Umstieg oder zu bestehenden Vereinbarungen hat, schreibt mir an [info@it-master.ch](mailto:info@it-master.ch). |

## Danke

SmallTime hat über viele Jahre Arbeitszeiten in Haushalten, Vereinen und kleinen Betrieben erfasst. Danke allen, die es genutzt, getestet, Fehler gemeldet und Code beigetragen haben. Dass das Projekt hier endet, fällt mir nicht leicht. Ihre Daten sollen aber in einer Lösung liegen, die sie über Jahre zuverlässig schützt.

Michael Heizmann, IT-Master Heizmann
