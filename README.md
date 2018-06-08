# Eigene Routen in Contao 4

**Achtung:** Dies ist ein erweitertes Tutorial für das [DummyBundle: https://github.com/Sioweb/Contao4DummyBundle](https://github.com/Sioweb/Contao4DummyBundle). In diesem Tutorial werden **Routen** erklärt.

Um eine eigene Route zu erzeugen, benötigt es einen Controller, eine weitere YML-Datei und einen Eintrag in der Plugin.php Datei.

## Plugin.php

Neu ist das `RoutingPluginInterface` und die Funktion `getRouteCollection`. Bitte auch die neuen Use-Angaben im Header beachten. Die Funktion lädt nun die Datei `routing.yml`, welche unter `Resources\Config\routing.yml` angelegt wird.

## Routing.yml

Jede Route benötigt einen YML-Block mit einer uniquen Bezeichnung. Idealerweise beschreibt die Bezeichnung, was die Route genau macht.

### Path-Option

Diese Option gibt den Pfad in der URL an. In diesem Beispiel lädt der Controller nur, wenn die URL mit `eure-domain.de/dummybundle/api/` beginnt. Die Reihenfolge der Routen ist wichtig, sobald Contao/Symfony die erste Route mit der URL `matchen` kann, wird diese Ausgeführt, egal ob  es noch eine Bessere gibt.

## Defaults

Wichtig ist hier vor allem die Option `_controller`. Sie besteht aus BundleName:ControllerName:Funktionsname

**ControllerName:** Der Name des Controllers, aber ohne das Wort Controller. Also bei `ApiController.php` darf lediglich `Api` verwendet werden.

**Funktionsname:** Genau wie bei Controllern, nur dass die Funktion ohne das Wort `Action` angegeben wird.

## Requirements

Damit kann angegeben werden, welche URL-Fragmente vorkommen müssen.
