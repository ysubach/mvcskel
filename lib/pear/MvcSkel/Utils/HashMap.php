<?php
/**
 * HashMap of objects that can be administered and searched, while hiding
 * the internal implementation. This is an implementation of the HashMap
 * class in the Java language.
 *
 * @author	Arnold Cano
 */
class MvcSkel_Utils_HashMap {
	/**
	 * @var	array
	 */
	var $_values = array();

	/**
	 * Create a HashMap with the specified values.
	 *
	 * @access	public
	 * @param	array	$values
	 */
	function HashMap($values = array())
	{
		if (!empty($values)) {
			$this->_values = $values;
		}
	}
	/**
	 * Removes all mappings from this map.
	 *
	 * @access	public
	 */
	function clear()
	{
		$this->_values = array();
	}
	/**
	 * Returns true if this map contains a mapping for the specified key.
	 *
	 * @access	public
	 * @param	mixed	$key
	 * @return	boolean
	 */
	function containsKey($key)
	{
		return array_key_exists($key, $this->_values);
	}
	/**
	 * Returns true if this map maps one or more keys to the specified value.
	 *
	 * @access	public
	 * @param	mixed	$value
	 * @return	boolean
	 */
	function containsValue($value)
	{
		return in_array($value, $this->_values);
	}
	/**
	 * Returns the value to which the specified key is mapped in this identity
	 * hash map, or null if the map contains no mapping for this key.
	 *
	 * @access	public
	 * @param	mixed	$key
	 * @return	mixed
	 */
	function get($key)
	{
		if ($this->containsKey($key)) { return $this->_values[$key]; }
	}
	/**
	 * Returns true if this map contains no key-value mappings.
	 *
	 * @access	public
	 * @return	boolean
	 */
	function isEmpty()
	{
		return empty($this->_values);
	}
	/**
	 * Returns an array of the keys contained in this map.
	 *
	 * @access	public
	 * @return	array
	 */
	function keySet()
	{
		return array_keys($this->_values);
	}
	/**
	 * Associates the specified value with the specified key in this map.
	 *
	 * @access	public
	 * @param	mixed	$key
	 * @param	mixed	$value
	 * @return	mixed
	 */
	function put($key, $value)
	{
		$previous = $this->get($key);
		$this->_values[$key] = $value;
		return $previous;
	}
	/**
	 * Copies all of the mappings from the specified map to this map. These
	 * mappings will replace any mappings that this map had for any of the keys
	 * currently in the specified map.
	 *
	 * @access	public
	 * @param	mixed	$values
	 */
	function putAll($values)
	{
		if (is_array($values)) {
			foreach ($values as $key => $value) {
				$this->put($key, $value);
			}
		}
	}
	/**
	 * Removes the mapping for this key from this map if present.
	 *
	 * @access	public
	 * @param	mixed	$key
	 */
	function remove($key)
	{
		$value = $this->get($key);
		if (!is_null($value)) { unset($this->_values[$key]); }
		return $value;
	}
	/**
	 * Returns the number of key-value mappings in this map.
	 *
	 * @access	public
	 * @return	integer
	 */
	function size()
	{
		return count($this->_values);
	}
	/**
	 * Returns an array of the values contained in this map.
	 *
	 * @access	public
	 * @return	array
	 */
	function values()
	{
		return $this->_values;
	}
}
