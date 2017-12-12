<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 22.11.2017
 * Time: 23:29
 */

namespace App\SparkPlug\Models;

use App\SparkPlug\Collections\AbstractBaseCollection as Collection;
use InvalidArgumentException;

/**
 * Class ModelCollection
 * @package App\SparkPlug\Models
 */
class ModelCollection extends Collection
{
    /**
     * Fügt Element Collection hinzu
     *
     * @param mixed $data
     *
     * @return array
     */
    public function add($data): array
    {
        if (is_object($data) && is_subclass_of($data, AbstractBaseModel::class)) {
            $this->data[] = $data;
        } else {
            throw new InvalidArgumentException("Only Models can be added");
        }

        return $this->data;
    }

    /**
     * Suche nach Element
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function find($id)
    {
        if (isset($this->data[$id])) {
            return $this->data[$id];
        }

        return null;
    }

    /**
     * Return the current element
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        if (isset($this->data[$this->currentPosition])) {
            return $this->data[$this->currentPosition];
        }

        return false;
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
        if (isset($this->data[$this->currentPosition])) {
            return true;
        }

        return false;
    }
}
