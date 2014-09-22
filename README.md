sqs-wrapper
===========

##Installaction

 - through composer
 
```bash
$> composer require oncesk/sqs-wrapper dev-master
```

##Usage

####Push (send) message

```php
<?php

use SqsWrapper\Manager;
use SqsWrapper\MessageAbstract;

class Message extends MessageAbstract {

	public $test = 1;
	public $array = array(1, 2);
	public $bool = true;
	public $string = 'hello world!';
	public $obj;
}

$client1 = Manager::createClient('KEY', 'SECRET', 'REGION');
$client2 = Manager::createClient('KEY', 'SECRET', \Aws\Common\Enum\Region::IRELAND);
$packer = new \SqsWrapper\Packer();

$manager = new Manager($client, 'Your Queue url');
$manager->setPacker($packer); // set packer for encode and decode message
$msg = new Message();
$msg->obj = new Message();
$msg->obj->string = 'nested object';
$manager->send($msg); // send message to current queue

$manager2 = new Manager($client, 'Your Queue url 2');
$manager2->setPacker($packer);

$collection = new \SqsWrapper\ManagerCollection();
$collection
	->addManager($manager)
	->addManager($manager2);

$msg = new Message();
$msg->obj = new Message();
$msg->obj->string = 'nested object';

$collection->send($msg); // send message in few queues

```

####Receive message

```php
<?php

use SqsWrapper\Manager;
use SqsWrapper\MessageAbstract;

class Message extends MessageAbstract {

	public $test = 1;
	public $array = array(1, 2);
	public $bool = true;
	public $string = 'hello world!';
	public $obj;
}

$client = Manager::createClient('KEY', 'SECRET', \Aws\Common\Enum\Region::IRELAND);

$manager = new Manager($client, 'Your Queue url');
$manager->setPacker(new \SqsWrapper\Packer());

$message = $manager->receive();
print_r($message);  // obj instance of Message
```
