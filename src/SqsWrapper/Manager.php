<?php
namespace SqsWrapper;

use Aws\Common\Enum\Region;
use Aws\Sqs\SqsClient;
use Guzzle\Service\Resource\Model;
use Guzzle\Common\Collection;
use Aws\Common\Enum\ClientOptions;

/**
 * Class Manager
 * @package SqsWrapper
 */
class Manager implements ManagerInterface {

	/**
	 * @var SqsClient
	 */
	private $client;

	/**
	 * @var PackerInterface
	 */
	private $packer;

	/**
	 * @var string
	 */
	private $queueUrl;

	/**
	 * @param string $key
	 * @param string $secret
	 * @param string $region
	 *
	 * @return SqsClient
	 */
	public static function createClient($key, $secret, $region = Region::IRELAND) {
		$config = new Collection(array(
			ClientOptions::KEY => $key,
			ClientOptions::SECRET => $secret,
			ClientOptions::REGION => $region
		));
		return SqsClient::factory($config);
	}

	/**
	 * @param SqsClient $client
	 * @param string    $queueUrl
	 */
	public function __construct(SqsClient $client, $queueUrl) {
		$this->client = $client;
		$this->queueUrl = $queueUrl;
	}

	/**
	 * @return string
	 */
	public function getQueueUrl() {
		return $this->queueUrl;
	}

	/**
	 * @param PackerInterface $packer
	 *
	 * @return $this
	 */
	public function setPacker(PackerInterface $packer) {
		$this->packer = $packer;
		return $this;
	}

	/**
	 * @return PackerInterface
	 */
	public function getPacker() {
		return $this->packer;
	}

	/**
	 * @throws \RuntimeException
	 *
	 * @return MessageInterface
	 */
	public function receive() {
		$packer = $this->getPacker();
		if (!$packer) {
			throw new \RuntimeException('Packed not defined');
		}
		$message = $this->client->receiveMessage(array(
			'QueueUrl' => $this->queueUrl
		));
		return $packer->decode($message);
	}

	/**
	 * @param MessageInterface $message
	 *
	 * @throws \RuntimeException
	 *
	 * @return Model
	 */
	public function send(MessageInterface $message) {
		$packer = $this->getPacker();
		if (!$packer) {
			throw new \RuntimeException('Packer not defined');
		}
		return $this->client->sendMessage(array(
			'QueueUrl' => $this->getQueueUrl(),
			'MessageBody' => $packer->encode($message)
		));
	}

	/**
	 * @param MessageInterface $message
	 *
	 * @return boolean
	 */
	public function delete(MessageInterface $message) {
		return $this->client->deleteMessage(array(
			'QueueUrl' => $this->getQueueUrl(),
			'ReceiptHandle' => $message->getReceiptHandle()
		));
	}
}