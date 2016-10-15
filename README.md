# TC
A PHP Injection Liblary.  

## Install 
```bash
composer require renshan/tc
```

## Usage

### Define A Service Without Parameter

```php
namespace Foo;

class Foo 
{
	public function foo()
	{
		// Do anything here
	}
}
```

```php
use Tc\Container;


$container = new Container;

$defKey = 'service.foo';
$defMap = 'Foo\Foo';

$container->register($defKey, $defMap);


```

### Define A Service With Parameters

```php

namespace Bar;

use Foo\Foo;

class Bar
{
	/**
		* This class need a parameter whic a instance of Foo, and a parameter $bar
		*/
	public function constructor(Foo $foo, $bar)
	{
	}

	public function bar()
	{
		// Do anything here
	}
}

```

and you can define a service like this\:

```php


$container = new Container;

$defKey = 'service.bar';
$defMap = 'Bar\Bar';
$params = ['@service.foo', 'bar']; // Be sure the position of parameters

$container->register($defKey, $defMap, $params);


```

finally, you can use your services now:

```php

$foo = $container->get('service.foo');
$bar = $container->get('service.bar');

```

and you can get a raw service\:

```php

$contnainer->raw('service.foo');

```

By default, a service will generate one and only a instance of the map class.
