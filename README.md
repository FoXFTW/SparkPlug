# SparkPlug
### A small PHP FrameWork

## Benötigte Software + Extensions:
PHP 7.x (http://be2.php.net/downloads.php)

### PHP Erweiterungen:

#### Linux:
* php7.x-sqlite3 - Datenbanktreiber  
 
#### Windows: (Erweiterungen in php.ini in der Form extension=<name>.dll einfügen)
php_pdo_sqlite
 
### Development:
* NodeJS
  * Windows: https://nodejs.org/en/
  * Linux: https://nodejs.org/en/download/package-manager/
 
 
## Installation:
### Development:
#### Benötigte PHP Erweiterungen:
* php7.x-xml
* php7.x-mbstring

#### Folgende Kommandos im Root-Verzeichnis ausführen:
* Linux: `apt-get install build-essential`

Nicht als Root (nur Linux) ausfüren:
* `npm install`
* `php composer.phar install`

Als Root (nur Linux) ausführen:
* `npm install --global gulp-cli`

##### Erstellen der minifizierten JS und CSS Dateien:
* `gulp default`

### Production + Development:
* `php composer.phar dump-autoload`
* Kopieren der Datei `storage/database/app_schema.sqlite` nach `storage/app/app.sqlite`
  * Derzeit wird nur SQLite unterstützt. Die Datei `app_schema.sqlite` enthält nur eine gunrdlegende User-Tabelle
* Anpassen des Upload-Limits in php.ini


## Start des Servers:
Ausführen im Root-Verzeichnis:
* `php -S localhost:80 -t public`  

Bei abweichender URL oder Port muss die URL unter `config/app.php` angepasst werden.  
Sollen Exception-Details ausgegeben werden, so muss unter `config/app.php` debug auf true gesetzt werden.


## Tests:
Tests dieser Software befinden sich im Ordner tests.  
Es wird zwingend PHPUnit benötigt (siehe Punkt Installation - Development).  
Zum Ausführen der Tests das entsprechende Kommando nutzen:  
* Windows: `vendor\bin\phpunit.bat --stderr --configuration phpunit.xml tests/`
* Linux `vendor\bin\phpunit --stderr --configuration phpunit.xml tests/`


## Dokumentation
### Templating Syntax
#### Views
##### Ein Template in einem View benutzen:
`@use('templateName')`  

Templates sind normale Views, mit dem Unterschied, dass in ihnen `@var('...')` Direktiven sind.


##### Inhalt eines Views in einen anderen View einfügen:  
`@include('viewName')`  

Lädt den Inhalt eines Views in den aktuellen View.  


##### Inhalt einer Variablen setzen:
`@set('varName', 'Inhalt')`  
Oder:
```
@set('varName')
Inhalt (bsp: PHP Code) 
@endset
```  

Die erste Version ist nützlich um einfache Inhalte in einer Variablen zu setzen (Beispielsweise Seitentitel).  
Die zweite Version erlaubt es beispielsweise HTML oder PHP-Code zu setzen.


##### Eine Route in einem View anzeigen lassen:
`@route('routeName')`

Gibt die URL einer benannten Route aus.  
**Beispiel:**  
Benannte Route aus web.php:  
```
[...]
$route->get('/impressum', 'PageController@showImpView')->name('impressum_Datenschutz');
[...]
```
Laden der Route in View:  
`<a href="@route('impressum_datenschutz')">Impressum</a>` ergibt folgendes in der Ausgabe:  
`<a href="/impressum">Impressum</a>`


##### Route mit Argument einfügen:  
`@route('routeName', 'arg')`  
`@route('routeName', 1)`


### Templates
#### Setzen von Variablen in Templates:
`@var('varName')`  
Zum Setzen der Inhalte siehe **Inhalt einer Variablen setzen**


### Views
#### Variablen einem View hinzufügen:
**Beispiel Route:** `$router->get('/ria/[0]', 'Ria\RiaController@showRiaDetailsView')->name('riaDetails');`  
Dem entsprechendem Controller muss nun ein Parameter hinzugefügt werden.  
**Beispiel Controller:** `public function showRiaDetailsView($id)`  
Um nun dem View die ID zu übergeben muss der Controller wie folgt genutzt werden:  
```php
    public function showRiaDetailsView($id)
    {
        $view = new View('ria.gallery');
        $view->setVars(
            [
                'id'   => $id,
            ]
        );

        return $view;
    }
```
Im View selber ist die ID dann unter der Variablen `$id` zu benutzen.  
Der Key des Arrays entspricht dem jeweiligen Variablennamen, der Wert dazu ist dann entsprechend der Wert der Variablen.  
Weitere Werte im Array werden genauso zu Variablen mit Werten.


### Routing
#### Route ohne Parameter definieren
Datei `routes/web.php` öffnen.  
*Wichtig:* Der Router matched der Reihe nach. Beispiel:
```php
$router->get('/login', 'User\Auth\LoginController@showLoginView')->name('login_form');
$router->get('/login', 'TestController@login')->name('login2');
```
Hier Würde *nicht* die Route mit Namen `login2` gematched werden, sondern nur die Route `login_form`.

#### GET Route
`$router->get('URI', 'Controller@Methode')->name('NameDerRoute');`

#### POST Route
`$router->post('URI', 'Controller@Methode')->name('NameDerRoute');`

### Route mit Parameter definieren
#### Liste der möglichen Parametertypen
*  `[a]` => nur Alphabetische Zeichen (a-z)
  *  Beispiel: `/route/abc`
*  `[a_]` => nur Alphabetische Zeichen (a-z) und `_`
  *  Beispiel: `/route/ab_cd`
*  `[0]` => nur Zahlen (0-9)
  *  Beispiel: `/route/123`
*  `[0_]` => nur Zahlen (0-9) und `_`
  *  Beispiel: `/route/12_34`
*  `[?]` => Alles
  *  Beispiel: `/route/1a-b_cd`

#### GET Route
`$router->get('URI/[0]', 'Controller@Methode')->name('NameDerRoute');`

#### POST Route
`$router->post('URI/[a]', 'Controller@Methode')->name('NameDerRoute');`

#### Update des Controllers
Um den definierten Parameter an einen Controller zu übergeben muss die entsprechende Methode um einen Parameter erweitert werden.  
Am Beispiel der GET Route:  
*Vorher:*  
```php
public function Methode()
{
...
}
```

*Nacher:*  
```php
public function Methode($parameter)
{
... Verarbeiten des Parameters
}
```