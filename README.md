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

Jedes weitere Bundle muss ebenfalls hinzugefügt werden.

## Credits

Danke an [Spookie](https://community.contao.org/de/member.php?9203-Spooky) für die [Hilfe im Forum](https://community.contao.org/de/showthread.php?66835-Gel%C3%B6st-Private-Bundles).  
Danke an [xtra](https://community.contao.org/de/member.php?503-xtra) für die [Hilfe im Forum](https://community.contao.org/de/showthread.php?64415-Unable-to-create-a-quot-contao-bundle-quot-under-contao-4-2-4&p=422990&viewfull=1#post422990).
