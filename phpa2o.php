<?php

class phpa2o implements \arrayaccess, \JsonSerializable, \iterator, \Countable
{
  public $original_row_array;

  public function __construct(&$row)
  {
    if (!is_array($row))
      throw new \Exception("PHPA2O support only arrays");
    $this->original_row_array = $row;
  }

  public function __invoke()
  {
    return $this->count();
  }

  public function count()
  {
    return count($this->original_row_array);
  }

  public function __set ( $name , $value )
  {
    $this->offsetSet($name, $value);
    return $this->__get($name);
  }

  public function __get ( $name )
  {
    return $this->offsetGet($name);
  }

  public function __isset ( $name )
  {
    return $this->offsetExists($name);
  }

  public function __unset ( $name )
  {
    return $this->offsetUnset($name);
  }

  public function offsetExists ( $name )
  {
    $o = &$this->original_row_array;
    return isset($o[$name]);
  }

  public function offsetGet ( $name )
  {
    if (!$this->offsetExists($name))
      return null;

    $o = &$this->original_row_array;
    return $this->RemainWrapper($o[$name]);
  }

  public function offsetSet($name, $value)
  {
      $o = &$this->original_row_array;
      if (is_null($name))
          $o[] = $value;
      else
          $o[$name] = $value;
  }

  public function offsetUnset ( $name )
  {
      unset($this->original_row_array[$name]);
  }

  public function jsonSerialize()
  {
      return $this->original_row_array;
  }

  public function rewind()
  {
      reset($this->original_row_array);
  }

  public function current()
  {
      $temp_variable_for_reference = current($this->original_row_array);
      return $this->RemainWrapper($temp_variable_for_reference);
  }

  public function key()
  {
      $temp_variable_for_reference = key($this->original_row_array);
      return $this->RemainWrapper($temp_variable_for_reference);
  }

  public function next()
  {
      $temp_variable_for_reference = next($this->original_row_array);
      return $this->RemainWrapper($temp_variable_for_reference);
  }

  public function valid()
  {
      return false !== $this->current();
  }

  private function RemainWrapper(&$data)
  {
      if (!is_array($data))
        return $data;
      return new phpa2o($data);
  }
}

