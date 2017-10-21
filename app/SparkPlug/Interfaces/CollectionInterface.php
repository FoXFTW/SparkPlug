<?php
/**
 * User: Hannes
 * Date: 21.10.2017
 * Time: 17:38
 */

namespace App\SparkPlug\Interfaces;

interface CollectionInterface
{
    public function add($data): array;

    public function find($id);

    public function all(): array;
}