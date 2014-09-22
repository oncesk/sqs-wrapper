<?php
namespace SqsWrapper;

use Guzzle\Service\Resource\Model;

/**
 * Class PackerInterface
 * @package SqsWrapper
 */
interface PackerInterface {

	/**
	 * @param MessageInterface $message
	 *
	 * @return string
	 */
	public function encode(MessageInterface $message);

	/**
	 * @param Model            $message
	 *
	 * @return MessageInterface[]
	 */
	public function decode(Model $message);
}