<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 21.10.2017
 * Time: 17:40
 */

namespace App\SparkPlug\Routing;

use App\SparkPlug\Interfaces\CollectionInterface;
use App\SparkPlug\Routing\Exceptions\RouteNotFoundException;
use Iterator;
use Countable;

/**
 * Class RoutingCollection
 *
 * @package App\SparkPlug\Routing
 */
class RoutingCollection implements CollectionInterface, Iterator, Countable
{
    private $routes = [];
    private $keys = [];
    private $currentPosition = 0;

    /**
     * @inheritdoc
     * @param mixed $route
     *
     * @return array
     */
    public function add($route): array
    {
        if (!$route instanceof Route) {
            throw new \InvalidArgumentException('Only Routes can be added');
        }

        $this->routes[$route->getRoute()] = $route;

        $this->keys[] = $route->getRoute();

        return $this->routes;
    }

    /**
     * @inheritdoc
     * @param mixed $id
     *
     * @return mixed
     * @throws \App\SparkPlug\Routing\Exceptions\RouteNotFoundException
     */
    public function find($id)
    {
        if (!isset($this->routes[$id])) {
            throw new RouteNotFoundException();
        }

        return $this->routes[$id];
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function all(): array
    {
        return $this->routes;
    }

    /**
     * Return the current element
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        if (isset($this->routes[$this->keys[$this->currentPosition]])) {
            $currentArenaID = $this->keys[$this->currentPosition];

            return $this->routes[$currentArenaID];
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
        if ($this->currentPosition + 1 <= count($this->routes)) {
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
        return count($this->routes);
    }
}
