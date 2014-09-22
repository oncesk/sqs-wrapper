<?php
namespace SqsWrapper;

/**
 * Class ManagerCollectionInterface
 * @package SqsWrapper
 */
interface ManagerCollectionInterface extends \IteratorAggregate, \Countable {

	/**
	 * @param ManagerInterface $manager
	 *
	 * @return $this
	 */
	public function addManager(ManagerInterface $manager);

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