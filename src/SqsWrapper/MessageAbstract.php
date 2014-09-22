<?php
namespace SqsWrapper;

/**
 * Class MessageAbstract
 * @package SqsWrapper
 */
abstract class MessageAbstract implements MessageInterface {

	/**
	 * @var string
	 */
	protected $receiptHandle;

	/**
	 * @param string $handle
	 *
	 * @return $this
	 */
	public function setReceiptHandle($handle) {
		$this->receiptHandle = $handle;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getReceiptHandle() {
		return $this->receiptHandle;
	}

	/**
	 * @return array
	 */
	public function getMessageData() {
		$data = array();
		$reflection = new \ReflectionClass($this);
		foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
			$data[$property->getName()] = $property->getValue($this);
		}
		return $data;
	}
}