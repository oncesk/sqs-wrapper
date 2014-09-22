<?php
namespace SqsWrapper;

use Aws\Sqs\SqsClient;
use Guzzle\Service\Resource\Model;

/**
 * Class ManagerInterface
 * @package SqsWrapper
 */
interface ManagerInterface {

	/**
	 * @param SqsClient $client
	 * @param string    $queueUrl
	 */
	public function __construct(SqsClient $client, $queueUrl);

	/**
	 * @param PackerInterface $packer
	 *
	 * @return $this
	 */
	public function setPacker(PackerInterface $packer);

	/**
	 * @return PackerInterface
	 */
	public function getPacker();

	/**
	 * @return string
	 */
	public function getQueueUrl();

	/**
	 * @throws \RuntimeException
	 *
	 * @return MessageInterface
	 */
	public function receive();

	/**
	 * @param MessageInterface $message
	 * @throws \RuntimeException
	 *
	 * @return Model
	 */
	public function send(MessageInterface $message);

	/**
	 * @param MessageInterface $message
	 *
	 * @return boolean
	 */
	public function delete(MessageInterface $message);
}