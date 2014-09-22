<?php
namespace SqsWrapper;


interface MessageInterface {

	/**
	 * @param string $handle
	 *
	 * @return $this
	 */
	public function setReceiptHandle($handle);

	/**
	 * @return string
	 */
	public function getReceiptHandle();

	/**
	 * @return array
	 */
	public function getMessageData();
}