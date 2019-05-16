# Contao 4.4+ DummyBundle

Ziel dieses Snippets soll es sein, ein privates Bundle einzubinden in den Contao workflow. In der Contao 4.3 Version gibt es keinen AppKernel oder ähnliches, deshalb wird hier ein externes Bundle definiert und via Composer in das vendor-Verzeichnis geladen. Danach funktioniert es wie ein regulär heruntergeladenes Bundle.

Dieses Repository dient als ein Tagebuch in dem ich nach und nach Lösungen für die Probleme notiere, welche mir während des Programmierens aufgetreten sind. Zu Beginn des Projektes, habe ich kaum Erfahrungen mit Composer, Yarn, RequireJS und nur einfache Kenntnisse über Symfony. Allerdings gleiche ich das mit jahrelanger Erfahrung wieder aus.

## Support/Fragen/Hilfe

Es gibt zwei Kategorien an Fragen:

- [Das Modul hier funktioniert nicht oder dir ist nicht klar was das soll](https://github.com/Sioweb/Contao4DummyBundle/issues)
- [Du hast Fragen zur Entwicklung mit Contao](https://community.contao.org/de/)

Sollte dir etwas in diesem Bundle fehlen, werde ich schauen ob ich es hinzufügen kann, oder du erstellst ein Pull-Request und erleichterst mir die Arbeit ab.

## Installieren

1. Kopiere den Ordner `/bundles/` in das Contao Root-Verzeichnis
2. Füge den Pfad zum Modul in der [Composer.json](https://github.com/Sioweb/Contao4DummyBundle/blob/master/example_composer.json#L41) hinzu
3. Registriere das Modul in der [Composer.json](https://github.com/Sioweb/Contao4DummyBundle/blob/master/example_composer.json#L21) und führe `composer update` in der Konsole aus
4. *ODER* installiere es über die Konsole mit `composer req sioweb/dummy-bundle`

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

### Modul registrieren / hinzufügen

Damit Composer das Modul finden kann, muss es noch in der composer.json registriert werden. Hier ein Beispiel: https://github.com/Sioweb/Contao4DummyBundle/blob/master/example_composer.json

## Bundle-Assets unter ROOT/web/bundles

Um die Assets aus dem Bundle in das öffentliche Verzeichnis zu laden, braucht das Bundle ein `public`-Verzeichnis:

- bundles
	- Sioweb
		- DummyBundle
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

## Contao Versionen

Zwischen Contao 4.4 und 4.6 bzw. 4.9 gibt es starke Veränderungen in der Art und Weise wie z.B. Hooks und Events verwendet werden können. In 4.6 müssen Hooks, nicht mehr in der config.php registriert werden, sondern können Symfony-Konform in einer listener.yml hinterlegt werden. Für die Abwärtskompatibilität, liefert das DummyBundle eine config.php mit erklärung mit.

Sieh dir hierzu auch mein [Glossar-Modul](https://github.com/Sioweb/glossar_c4) an, welches für Contao <= 4.6 eine Art "Fake-Polyfill" verwendet, um die Events aus der `config.php` zu laden.

## Eigene Routen

### Plugin.php

Neu ist das `RoutingPluginInterface` und die Funktion `getRouteCollection`. Bitte auch die neuen Use-Angaben im Header beachten. Die Funktion lädt nun die Datei `routing.yml`, welche unter `Resources\Config\routing.yml` angelegt wird.

### Routing.yml

Jede Route benötigt einen YML-Block mit einer uniquen Bezeichnung. Idealerweise beschreibt die Bezeichnung, was die Route genau macht.

#### Path-Option

Diese Option gibt den Pfad in der URL an. In diesem Beispiel lädt der Controller nur, wenn die URL mit `eure-domain.de/dummybundle/api/` beginnt. Die Reihenfolge der Routen ist wichtig, sobald Contao/Symfony die erste Route mit der URL `matchen` kann, wird diese Ausgeführt, egal ob  es noch eine Bessere gibt.

#### ControllerName

Der Name des Controllers, aber ohne das Wort Controller. Also bei `ApiController.php` darf lediglich `Api` verwendet werden.

#### Funktionsname

Genau wie bei Controllern, nur dass die Funktion ohne das Wort `Action` angegeben wird.

## Entwicklung on the fly

Um ein Modul zu entwickeln, empfehle ich den Entwicklermodus, der wie üblich in Symfony über domain.tld/app_dev.php aufgerufen werden kann.

Bei erstmaliger Benutzung muss zuerst ein Zugang für den Modus per Konsole erzeugt werden:

	php vendor/bin/contao-console contao:install-web-dir --user=USERNAME --password=PASSWORT

Durch diesen Befehl wird app_dev.php durch ein Passwort gesichert.

## Satis & Gitlab / Github ODER [packagist.com](https://packagist.com)

Falls du vor hast, viele Module privat zu verwalten und keine Lust hast, die immer lokal per FTP zu installieren, empfehle ich dir, deine Pakete gesammelt in ein privates Verzeichnis im Netz zu installieren. Dazu hast du drei Möglichkeiten: GIT, Packagist.com oder [Satis](https://getcomposer.org/doc/articles/handling-private-packages-with-satis.md).

### Git

Composer kann auf Git-Repositories zugreifen, dazu musst in der `Root/composer.json` im `repositories`-Block etwa folgendes Notiert werden:

	"repositories": [
	    {
		"url": "https://domain-zu-einem-git-repository.git",
		"type": "git"
	    }
	]

### Packagist.com

Packagist.com ist im Gegensatz zu [Packagist.org](https://packagist.org) kostenpflichtig. Allerdings ist die Platform sehr bequem und einfach zu bedienen und bietet private Pakete an ohne viel einarbeitungszeit wie etwa bei Satis.

### Satis 

Satis benötigt eine Domain etwa packages.deine-domain.de, welche nach der installation von Satis in das /web/ verzeichnis von Satis zeigt. Am einfachsten ist es, Gitlab via [SSH verbinden zu lassen](https://medium.com/uncaught-exception/setting-up-multiple-gitlab-accounts-82b70e88c437).

#### Mein Workflow

1. Modul auf via Git auf Gitlab pushen.
- In Gitlab einen Tag mit Version und changes anlegen.
- Gitlab Interactions empfangen das Tag-Event und rufen eine PHP-Datei in Satis auf (.php?package=sioweb/packagename)
	- Gitlab sendet dazu einen Security-Token den die PHP-Datei validiert
- Satis lädt das neue Paket

Danach kann das Modul überall mit `composer req sioweb/packagename` installiert werden, wenn das Satis-Repository im `repositories`-Block hinterlegt ist.

#### Sicherheit

Das Satis-Verzeichnis sollte per `.htaccess` geschützt werden, mit einer sogenannten `auth.json` können die Zugangsdaten an Composer übermittelt werden.

## Lizenz? Muss Sioweb irgendwo stehen bleiben?

Nö. Lade das Bundle herunter, suche und ersetze rekursiv `Sioweb` mit `deinem Alias` und DummyBundle durch den Namen den du dem Bundle geben willst. Achte einfach noch auf den Pfad und dass alle Dateien dein `Naming` besitzen, damit Composer alle Daten findet.

Gerade hier schleichen sich die meisten Fehler ein

- Copy&PAst
- Typos in Namen
- Vergessen in einigen Dateien die Namen / Namespaces anzupassen

Kommentare die ich aus `Tutorialgründen` notiert habe, sollten auf jeden Fall entfernt werden. Einfach um das eigene Image zu wahren.

## Doctrine

### Vorab

Du kannst diese Einstellungen auch direkt in deinem Modul einrichten, du musst dazu nicht die composer.json oder die config.yml von Contao anfassen. In diesem DummyBundle ist ein Beispiel zu finden, wie du Doctrine, Entities und Repositories verwendest.

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

## Dir gefällt das Bundle?

Gerne freue ich mich über ein kleines Danke via [Amazon Wunschliste](https://www.amazon.de/hz/wishlist/ls/3IW6TE09RDGV2/ref=nav_wishlist_lists_1?_encoding=UTF8&type=wishlist).

Du kannst Amazon nicht leiden? Kein Problem, ich freue mich auch über [Likes](https://www.facebook.com/sioweb) und [positive Bewertungen](https://www.google.de/search?q=Sioweb).

## Ende

Hier währe das Dokument fast über den Rand des Internets gefallen, weil es so lang ist ...
