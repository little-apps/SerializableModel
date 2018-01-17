<?php
namespace LittleApps\SerializableModel;

trait Serializable {
	/**
	* Checks if value is serialized using serialize()
	* @param string $value Non-empty string
	* 
	* @return bool True if value is serialized
	*/
    protected function isSerialized($value) {
		if (!is_string($value) || trim($value) == '')
			return false;
			
		return ($value == serialize(false) || @unserialize($value) !== false);
	}
	
	/**
	* Only serializes a value if it is not a resource or string
	* @param mixed $value
	* 
	* @return string|bool Returns false if value is a resource, otherwise, a string.
	*/
	protected function maybeSerialize($value) {
		if (is_resource($value))
			// Can't store resources
			return false;
		else if (is_string($value))
			// Don't serialize strings
			return $value;
		else
			// Serialize value
			return serialize($value);
	}
	
	/**
	* Gets attributes that are serializable from $serializable property
	* 
	* @return array An empty array if $serializable property does not exist, otherwise, the $serializable property.
	*/
	public function getSerializable() {
		return property_exists($this, 'serializable') ? $this->serializable : [];
	}
	
	/**
	* Overrides the cast type if the attribute is serializable
	* @param string $key
	* 
	* @return string Returns 'string' if attribute is serializable
	*/
	protected function getCastType($key) {
		if (in_array($key, $this->getSerializable()))
			return 'string';
			
		return parent::getCastType($key);
	}
	
	/**
	* If attribute is serialized, unserializes the attribute value before being returned
	* @param string $key
	* 
	* @return mixed Attribute value unserialized (if its serializable)
	*/
	public function getAttributeValue($key) {
		$value = parent::getAttributeValue($key);
		
		if (in_array($key, $this->getSerializable())) {
			$value = $this->isSerialized($value) ? unserialize($value) : $value;
		}
		
		return $value;
	}
	
	/**
	* If attribute is serialized, serializes the attribute value before being set
	* @param string $key
	* @param mixed $value 
	* 
	* @return self
	*/
	public function setAttribute($key, $value) {
		if (in_array($key, $this->getSerializable())) {
			$this->attributes[$key] = $this->maybeSerialize($value);
				
			return $this;
		}
		
		return parent::setAttribute($key, $value);
	}
}
