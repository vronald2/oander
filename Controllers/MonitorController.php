<?php

namespace Oander\Controllers;

use Oander\Models\EavEntity;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Oander\Models\Paginator;

class MonitorController
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    /**
     * @var Environment
     */
    protected $twig;

    /**
     *
     */
    public function __construct()
    {
        global $entityManager;
        $this->entityManager = $entityManager;
        $loader = new FilesystemLoader('./templates');
        $this->twig = new Environment($loader);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {

        $filterForm = isset($_GET['filter_form']) ? $_GET['filter_form'] : [];

        $qb = $this->entityManager->createQueryBuilder();

        $qb->select(array('m', 'av', 'a'))
            ->from(EavEntity::class, 'm')
            ->leftJoin('m.attributeValues', 'av')
            ->leftJoin('av.attribute', 'a')
            ->setFirstResult(0)
            ->setMaxResults(10);

        $this->applyFilters($filterForm, $qb);

        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        $paginator = new Paginator();
        $paginator->paginate($qb->getQuery(), $page);

        return $this->twig->render('monitors.twig', [
            'paginator' => $paginator,
            'filter_form' => $filterForm,
            'page' => $page
        ]);
    }

    /**
     * @param $filterForm
     * @param $qb
     * @return void
     */
    private function applyFilters($filterForm, $qb)
    {

        $params = [];

        if (isset($filterForm['size']) && $filterForm['size'] !== "") {
            $qb->orWhere('av.value = :size_value AND a.code = :size_code');

            $params = array_merge($params, [
                'size_value' => $filterForm['size'],
                'size_code' => 'size',
            ]);
        }

        if (isset($filterForm['brand']) && $filterForm['brand'] !== "") {
            $qb->orWhere('av.value LIKE :brand_value AND a.code = :brand_code');
            $params = array_merge($params, [
                'brand_value' => "%" . $filterForm['brand'] . "%",
                'brand_code' => 'brand',
            ]);
        }


        if (isset($filterForm['resolution']) && $filterForm['resolution'] !== "") {
            $qb->orWhere('av.value LIKE :resolution_value AND a.code = :resolution_code');
            $params = array_merge($params, [
                'resolution_value' => $filterForm['resolution'],
                'resolution_code' => 'resolution',
            ]);
        }

        if (isset($filterForm['price']['from']) && $filterForm['price']['from'] !== "") {
            $qb->orWhere('INT(av.value) > :price_from_value AND a.code = :price_from_code');
            $params = array_merge($params, [
                'price_from_value' => $filterForm['price']['from'],
                'price_from_code' => 'price',
            ]);
        }

        if (isset($filterForm['price']['to']) && $filterForm['price']['to'] !== "") {
            $qb->orWhere('INT(av.value) < :price_to_value AND a.code = :price_to_code');
            $params = array_merge($params, [
                'price_to_value' => $filterForm['price']['to'],
                'price_to_code' => 'price',
            ]);
        }

        $qb->setParameters(
            $params
        );

    }
}
