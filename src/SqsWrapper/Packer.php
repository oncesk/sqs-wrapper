<?php
namespace SqsWrapper;

use Guzzle\Http\Message\MessageInterface;
use Guzzle\Service\Resource\Model;

/**
 * Class Packer
 * @package SqsWrapper
 */
class Packer implements PackerInterface {

	/**
	 * @param MessageInterface $message
	 *
	 * @return array|mixed|string
	 */
	public function encode(MessageInterface $message) {
		return $this->toJson($message->getMessageData(), get_class($message));
	}

	/**
	 * @param Model            $message
	 *
	 * @return MessageInterface[]
	 */
	public function decode(Model $message) {
		$messages = array();
		if (count($message['Messages']) > 0) {
			foreach ($message['Messages'] as $amazonMessage) {
				$decodeResult = $this->fromArrayToObject(json_decode($amazonMessage['Body'], true), $amazonMessage['ReceiptHandle']);
				if ($decodeResult) {
					$messages[] = $decodeResult;
				}
			}
		}
		return $messages;
	}

	/**
	 * @param array $data
	 * @param string $receiptHandle
	 * @throws \RuntimeException
	 *
	 * @return MessageInterface|bool
	 */
	protected function fromArrayToObject(array $data = array(), $receiptHandle = null) {
		if (empty($data)) {
			return false;
		}
		if (!class_exists($data['class'])) {
			throw new \RuntimeException('Class' . $data['class'] . ' is not defined');
		}
		$object = new $data['class']();
		if ($object instanceof MessageInterface) {
			if ($receiptHandle) {
				$object->setReceiptHandle($receiptHandle);
			}
			foreach ($data['properties'] as $property) {
				if (isset($property['value']['class'])) {
					$nestedObject = $this->fromArrayToObject($property['value'], $receiptHandle);
					if ($nestedObject) {
						$object->{$property['name']} = $nestedObject;
					}
				} else {
					$object->{$property['name']} = $property['value'];
				}
			}
		} else {
			throw new \RuntimeException('Message should be instance of MessageInterface');
		}
		return $object;
	}

	/**
	 * @param array  $data
	 * @param string $class
	 * @param bool   $returnString
	 *
	 * @return array|string
	 */
	protected function toJson(array $data = array(), $class, $returnString = true) {
		$json = array(
			'class' => $class,
			'properties' => array()
		);
		foreach ($data as $key => $value) {
			if (is_object($value) && $value instanceof MessageInterface) {
				$json['properties'][] = array(
					'name' => $key,
					'value' => $this->toJson($value->getMessageData(), get_class($value), false)
				);
			} else {
				if (is_array($value) && isset($value[0]) && $value[0] instanceof MessageInterface) {
					$props = array(
						'name' => $key,
						'value' => array()
					);
					foreach ($value as $k => $obj) {
						$props['value'][] = $this->toJson($obj->getMessageData(), get_class($obj), false);
					}
					$json['properties'][] = $props;

				} else {
					$json['properties'][] = array(
						'name' => $key,
						'value' => $value
					);
				}
			}
		}
		return $returnString ? json_encode($json) : $json;
	}
}