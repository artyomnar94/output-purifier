# output-purifier
Purifies text from unnecessary tags, external-sources and scripts

[![Latest Stable Version](https://poser.pugx.org/artyomnar/output-purifier/v/stable.png)](https://packagist.org/packages/artyomnar/output-purifie)
[![Total Downloads](https://poser.pugx.org/artyomnar/output-purifier/downloads.png)](https://packagist.org/packages/artyomnar/output-purifier)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require artyomnar/output-purifier
```

or add

```
"artyomnar/output-purifier": "*"
```

to the require section of your composer.json.

Usage
------------
### Saving filtered text into DB:
```
$rawText = '<p><h1 class="header">Hello, world!</h1></p><img src="https://mysite.com/img/logo.svg"><a href="https://wrong-site.com/js/xss-atack.js">Go!</a>';
$purifier = Purifier::getInstance(['img', 'a', 'p'], ['https://mysite.com'], '#');
$db->save($purifier->filter($rawText));

//Result:<p>Hello, world!</p><img src="https://mysite.com/img/logo.svg"><a href="#/js/xss-atack.js">Go!</a>
```
### Displaying filtered text on view:
```
$rawText = '<p id="w1"><h1 class="header">Hello, world!</h1></p><img src="https://mysite.com/img/logo.svg"><img src="https://wrong-site.com/js/xss-atack.js">Go to the link https://xxx.com <div style="background-image: url("https://hacker.com/xss/script.js")"></div>';
$purifier = Purifier::getInstance(['img', 'a', 'p', 'div'], ['https://mysite.com', 'https://static.mysite.com'], 'https://valid-site.com');
echo $purifier->filter($rawText);

//Result:<p id="123">hello, world!</p><img src="https://mysite.com/img/logo.svg"><img src="https://valid-site.com/js/xss-atack.js">Go to the link https://valid-site.com <div style="background-image: url("https://valid-site.com/xss/script.js")"></div>
```