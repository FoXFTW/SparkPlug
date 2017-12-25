<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 21.10.2017
 * Time: 17:40
 */

namespace App\SparkPlug\Routing;

use App\SparkPlug\Collections\AbstractBaseCollection as Collection;
use App\SparkPlug\Routing\Exceptions\RouteNotFoundException;

/**
 * Class RoutingCollection
 */
class RoutingCollection extends Collection
{
    /**
     * @inheritdoc
     *
     * @param mixed $route
     *
     * @return array
     */
    public function add($route): array
    {
        if (!$route instanceof Route) {
            throw new \InvalidArgumentException('Only Routes can be added');
        }

        $this->data[$route->getRoute()] = $route;

        $this->keys[] = $route->getRoute();

        return $this->data;
    }

    /**
     * @inheritdoc
     *
     * @param mixed $id
     *
     * @return mixed
     *
     * @throws \App\SparkPlug\Routing\Exceptions\RouteNotFoundException
     */
    public function find($id)
    {
        if (!isset($this->data[$id])) {
            throw new RouteNotFoundException();
        }

        return $this->data[$id];
    }
}
