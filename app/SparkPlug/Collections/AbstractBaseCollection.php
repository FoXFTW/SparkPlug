<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 22.11.2017
 * Time: 23:34
 */

namespace App\SparkPlug\Collections;

use Iterator;
use Countable;

abstract class AbstractBaseCollection implements CollectionInterface, Iterator, Countable
{
    protected $data = [];
    protected $keys = [];
    protected $currentPosition = 0;

    public function all(): array
    {
        return $this->data;
    }

    /**
     * Return the current element
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        if (isset($this->data[$this->keys[$this->currentPosition]])) {
            $currentArenaID = $this->keys[$this->currentPosition];

            return $this->data[$currentArenaID];
        }

        return false;
    }

    /**
     * Move forward to next element
     * @link  http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        if ($this->currentPosition + 1 <= count($this->data)) {
            $this->currentPosition++;
        }
    }

    /**
     * Return the key of the current element
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        if (isset($this->keys[$this->currentPosition])) {
            return $this->keys[$this->currentPosition];
        }

        return null;
    }

    /**
     * Checks if current position is valid
     * @link  http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        if (isset($this->keys[$this->currentPosition])) {
            return true;
        }

        return false;
    }

    /**
     * Rewind the Iterator to the first element
     * @link  http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->currentPosition = 0;
    }

    /**
     * Count elements of an object
     * @link  http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->data);
    }

    public function __toString()
    {
        $return = "";

        foreach ($this->data as $data) {
            $return .= "{$data}\n";
        }

        return $return;
    }
}
