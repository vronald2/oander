<?php

namespace Oander\Models;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as OrmPaginator;


class Paginator
{

    /**
     * @var
     */
    private $total;
    /**
     * @var
     */
    private $lastPage;
    /**
     * @var
     */
    private $items;

    /**
     * @param QueryBuilder|Query $query
     * @param int $page
     * @param int $limit
     * @return Paginator
     */
    public function paginate($query, int $page = 1, int $limit = 10): Paginator
    {
        $paginator = new OrmPaginator($query);

        $paginator
            ->getQuery()
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResults($limit);

        $this->total = $paginator->count();
        $this->lastPage = (int) ceil($paginator->count() / $paginator->getQuery()->getMaxResults());
        $this->items = $paginator;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }
}