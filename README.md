# Contao 4.3 DummyBundle

Ziel dieses Snippets soll es sein, ein privates Bundle einzubinden in den Contao workflow. In der Contao 4.3 Version gibt es keinen AppKernel oder ähnliches, deshalb wird hier ein externes Bundle definiert und via Composer in das `vendor`-Verzeichnis geladen. Danach funktioniert es wie ein regulär heruntergeladenes Bundle.

## Generell

Das `bundles`-Verzeichnis muss im Root von Contao stehen. Ggf. kann der Pfad in der `ROOT/composer.json` sonst auch angepasst werden.

## Root composer.json

Das Bundle muss in den `require`-Block geschrieben werden, danach muss der Pfad zum Bundle in den `repositories`-Block geschrieben wereden. Der erweiterte Aufbau der composer.json sieht dann in etwa wie folgt aus. Bitte keine Daten löschen die gebraucht werden, es sollen nur die neuen Daten hinzugefügt werden.

	{
		...
		"require": {
		    	"php": ">=5.5.0",
		    	"contao/manager-bundle": "4.3.*",
		    	"contao/calendar-bundle": "^4.3",
			...
			"sioweb/dummy-bundle": "^1.0"
		},
		...
		"repositories": [
			{
				"type": "path",
				"url": "bundles/DummyBundle"
			}
		]
		...
	}

Jedes weitere Bundle muss ebenfalls hinzugefügt werden. Die Punkte (...) dürfen **nicht** übernommen werden.

## Credits

Danke an [Spookie](https://community.contao.org/de/member.php?9203-Spooky) für die [Hilfe im Forum](https://community.contao.org/de/showthread.php?66835-Gel%C3%B6st-Private-Bundles).  
Danke an [xtra](https://community.contao.org/de/member.php?503-xtra) für die [Hilfe im Forum](https://community.contao.org/de/showthread.php?64415-Unable-to-create-a-quot-contao-bundle-quot-under-contao-4-2-4&p=422990&viewfull=1#post422990).

## Installieren

### Composer (Windows)

Damit das Plugin installiert werden kann, muss [Composer](https://getcomposer.org/download/) lokal oder global installiert werden. Früher konnte Composer einfach als Administrator unter Windows verwendet werden, in der neuesten Version muss die Console (CMD oder MinGw Git Bash) als Administrator gestartet werden, damit Composer die Symlinks korrekt setzen kann.

Nun muss mit der Console der Contao-Root aufgerufen werden. Composer soll dann die Updates installieren und der Cache muss geleert werden. Folgende Befehle müssen ausgeführt werden:

	cd /path/to/contao
	composer update
	vendor/bin/contao-console cache:clear

Composer installiert das Plugin nun. Nach der Installation kann beliebig an den Bundle gearbeitet werden. Sobald es neue Klassen und Dateien gibt, muss ggf. der Cache geleert werden und die Autoload-Files müssen ggf. neu generiert werden.

	composer dump-autoload
	vendor/bin/contao-console cache:clear

Sollten die Änderungen nicht greifen, kann zusätzlich das Verzeichnis `/var/cache` von Hand oder mit der Console gelöscht werden.

## Google Begriffe

Symfony service, Bundles, Composer PSR-4, Composer repositories, Composer, Git-Bash als Administrator
