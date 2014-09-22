<?php
namespace SqsWrapper;

use Guzzle\Service\Resource\Model;

/**
 * Class ManagerCollection
 * @package SqsWrapper
 */
class ManagerCollection implements ManagerCollectionInterface {

	/**
	 * @var ManagerInterface[]
	 */
	protected $managers = array();

	/**
	 * @param ManagerInterface $manager
	 *
	 * @return $this
	 */
	public function addManager(ManagerInterface $manager) {
		$this->managers[] = $manager;
		return $this;
	}

	/**
	 * @param MessageInterface $message
	 *
	 * @throws \RuntimeException
	 *
	 * @return Model[]
	 */
	public function send(MessageInterface $message) {
		$result = array();
		foreach ($this->managers as $manager) {
			$result[$manager->getQueueUrl()] = $manager->send($message);
		}
		return $result;
	}

	/**
	 * @param MessageInterface $message
	 *
	 * @return boolean[]
	 */
	public function delete(MessageInterface $message) {
		$result = array();
		foreach ($this->managers as $manager) {
			$result[$manager->getQueueUrl()] = $manager->delete($message);
		}
		return $result;
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Retrieve an external iterator
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return \Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 */
	public function getIterator() {
		return new \ArrayIterator($this->managers);
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Count elements of an object
	 * @link http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * </p>
	 * <p>
	 *       The return value is cast to an integer.
	 */
	public function count() {
		return count($this->managers);
	}
}