# Contao 4.x DummyBundle

Ziel dieses Snippets soll es sein, ein privates Bundle einzubinden in den Contao workflow. In der Contao 4.3 Version gibt es keinen AppKernel oder ähnliches, deshalb wird hier ein externes Bundle definiert und via Composer in das `vendor`-Verzeichnis geladen. Danach funktioniert es wie ein regulär heruntergeladenes Bundle.

Dieses Repository dient als ein Tagebuch in dem ich nach und nach Lösungen für die Probleme notiere, welche mir während des Programmierens aufgetreten sind. Zu Beginn des Projektes, habe ich kaum Erfahrungen mit Composer, Yarn, RequireJS und nur einfache Kenntnisse über Symfony. Allerdings gleiche ich das mit jahrelanger Erfahrung wieder aus.

## Packagist

Falls das Bundle bzw Modul nicht privat sein soll, muss es lediglich auf [Packagist](https://packagist.org/) veröffentlicht werden. Dazu wird ein Konto bei Packagist benötigt und ggf. ein Github-Konto oder wo auch immer das Modul als .git zu finden ist. Packagist stellt das Modul dann bereit.

Wichtig ist, in Github kann unter den Settings ein Service (Integrations & service) definiert werden. Hier muss packagist als Service ausgewählt werden und dann pusht Github jedes neue Release (Tags) direkt auf Packagist. Alle Nutzer des großen weiten Internets haben dann Zugriff auf das Modul.

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

## Bundle-Assets unter ROOT/web/bundles

Um die Assets aus dem Bundle in das öffentliche Verzeichnis zu laden, braucht das Bundle ein `public`-Verzeichnis:

- Bundle
	- src
		- ContaoManager
		- Ressources
			- contao
			- **public**

Nach der Installation bzw. wenn das Verzeichnis nachträglich hinzugefügt wurde, müssen die Assets noch installiert werden. Ich nutze dafür die Konsole:

	cd "to/my/dir"
	vendor/bin/contao-console assets:install --symlink
	
Danach sollten die Assets unter `ROOT/web/bundles/DEIN_BUNDLE` zu finden sein.

**Hinweis:** Ohne die Option `--symlink` werden die Assets (Bilder, CSS, Javascript) nur in das Assets-Verzeichnis kopiert. Bei jeder Anpassung, muss der Befehl dann neu ausgeführt werden. Bei einem Symlink, sind die Anpassungen in den Assets direkt nach speichern verfügbar.

## Entwicklung on the fly

Um ein Modul zu entwickeln, empfehle ich den Entwicklermodus, der wie üblich in Symfony über domain.tld/app_dev.php aufgerufen werden kann.

Bei erstmaliger Benutzung muss zuerst ein Zugang für den Modus per Konsole erzeugt werden:

	php vendor/bin/contao-console contao:install-web-dir --user=USERNAME --password=PASSWORT

Durch diesen Befehl wird app_dev.php durch ein Passwort gesichert.

## Doctrine

### Nützliche Erweiterungen für Doctrine

Diese Symfony-Bundles nutze ich gerne in meinen Apps, da sie mehr Datentypen als nur die Standards beherrschen wie `Enum`.

    "require": {
    	...
        "doctrine/orm": "^2.5",
        "doctrine/doctrine-bundle": "^1.6",
        "doctrine/doctrine-cache-bundle": "^1.2",
        "doctrine/data-fixtures": "1.0.*",
        "doctrine/dbal": "~2.4",
        "wiredmedia/doctrine-extensions": "*",
        "oro/doctrine-extensions": "*"

### Beispiel

Damit Doctrine mit Entities funktionieren, muss in der `ROOT/app/config/config.yml` folgende Zeilen hinzugefügt werden. Die Zugangsdaten wurden bei der Installation in die `parameters.yml` gespeichert.

	doctrine:
	    dbal:
	        default_connection: default
	        connections:
	            default:
	                driver:   pdo_mysql
	                host:     "%database_host%"
	                port:     "%database_port%"
	                user:     "%database_user%"
	                password: "%database_password%"
	                dbname:   "%database_name%"
	                charset:  UTF8
	    orm:
	        entity_managers:
	            default:
	                mappings:
	                    ErstesBundleMitEntities: ~
	                    ZweitesBundleMitEntities: ~
						SiowebDummyBundle: ~

### PSR-4

Doctrine kann nicht mit PSR-4 arbeiten. Allerdings gibt es [hier einen Patch](https://gist.github.com/janvennemann/46b2626eee2a4808ed75) den du in dein System einpflegen musst. Erstelle unter `ROOT/app/` ein Verzeichnis an, am besten `/vendor`. Danach speicher die Klasse `DisconnectedMetadataFactory` in diesen Ordner.

Am einfachsten wieder über die Konsole:

	cp vendor/doctrine/doctrine-bundle/Mapping/DisconnectedMetadataFactory.php app/vendor/DisconnectedMetadataFactory.php

Sobald die Klasse eingefügt wurde, muss das Patch eingespeichert werden und in der `ROOT/composer.json` fehlt nun noch folgendes:

	
    "autoload": {
        "classmap": ["app/vendor"]
    }

### Probleme mit Enum

An dieser Stelle muss `enum` noch als Mapping in die Datei `ROOT/app/config/config.yml` notiert werden:

	doctrine:
	    dbal:
	        ...
	        connections:
	            default:
					...
					...
	                mapping_types:
	                    enum: string

## Bower / Yarn und requirejs

**Warum jetzt Node?**: Weil's geht. Alle Componenten lassen sich auch mit Composer herunterladen. In einem öffentlichen Bundle ist es sogar klüger, da die Abhängigkeiten direkt installiert werden können, ohne das Redundanzen entstehen. Warum also Yarn? Ich wollte testen ob es geht und wie ich einen schönen Workflow aufbauen kann. Fakt ist, Yarn ist noch nicht soweit und in Composer lassen sich auf jedenfall Zielordner besser angeben als mit Yarn/Bower. Wer aber wie ich einen bestehenden Workflow mit Yarn nutzt kann es ja nutzen. 

Mit Yarn lassen sich wie mit Composer weitere Pakete aus dem Web herunterladen. Yarn deckt dabei vor allem den Javascript und NodeJS bereich ab. [Yarn lässt sich leicht installieren](https://yarnpkg.com/en/docs/install). Bei mir ist es global installiert, dazu habe ich `npm install -g yarn` verwendet (npm ist ebenfalls global installiert).

Ich wechsle in das `ROOT/files/assets` Verzeichnis, in dem ich alle meine Scripte, Bilder und Styles speichere. Um als Beispiel ChartJS, NoUISlider und RequireJS zu installieren benötigt es folgende package.json in diesem Verzeichnis.

	{
		"dependencies": {
			"chart.js": "^2.5.0",
			"nouislider": "^9.2.0",
			"requirejs": "^2.3.3"
		}
	}

Sobald die Datei bereit liegt, können die Abhängigkeiten via Yarn in der Konsole installiert werden:

	yarn install

Yarn erstellt das Verzeichnis node_modules in diesem Verzeichnis. Von hier aus können nun alle Libraries geladen werden.

## RequireJS

Ich habe noch nicht viel Erfahrung mit RequireJS, daher kann es sein, dass hier totaler funktionierender Mist steht :)

### Vorbereitung 

Damit das Funktioniert, muss die Verzeichnisstruktur wie im vorherigen Punkt eingehalten werden. Wenn RequireJS installiert wurde, muss es noch am Ende der fe_page.html5 notiert werden. Zusätzlich muss unter `ROOT/files/assets/js/` die Datei main.js angelegt werden.

	
	<script data-main="files/assets/js/main" src="files/assets/node_modules/requirejs/require.js"></script>

Damit die Bundles mit RequireJS arbeiten können, empfehle ich einen Loader in die Datei `main.js` zu notieren.

	if(window.tl_requirejs !== undefined) {
		for(var tl_rjs_key in window.tl_requirejs) {
			window.tl_requirejs[tl_rjs_key](require);
		}
	}

Im Head der `fe_page.html` fehlt noch: 

	<script>window.tl_requirejs= [];</script>

.. damit keine Fehler wegen undefinierten Variablen entstehen.

### Im Bundle nutzen

Wir wollen an dieser stelle ChartJS und noUiSlider verwenden.

Im Bundle unter `src/public/js` wird die Datei `main.js` erstellt mit dem Inhalt:

	window.tl_requirejs.push(function(require) {
		require.config({
			baseUrl: '/',
			paths: {
				'chartjs': '/files/assets/node_modules/chart.js/dist/Chart.min',
				'nouislider': '/files/assets/node_modules/nouislider/distribute/nouislider.min',
			}
		});
	
		require(['chartjs','nouislider'],function(Chart,noUiSlider) {
			// new Chart();
			// new noUiSlider();
		});
	});

Der Code registriert eine Funktion die dann in der Datei `Root/files/assets/js/main.js` ausgeführt wird. Danach wird RequireJS konfiguriert in der Funktion und lädt die beiden Tools nach. Schlussendlich werden die Tools über `require()` geladen.

## Google Begriffe

Symfony service, Bundles, Composer PSR-4, Composer repositories, Composer, Git-Bash als Administrator

## Nice2Know / F.A.Q

### Wie sehe ich, welche Parameter wie %kernel.root_dir% im Core abrufbar sind?

	$ vendor/bin/contao-console debug:container --parameters
	
### Welche Routen sind derzeit erreichbar?

	$ vendor/bin/contao-console debug:router
