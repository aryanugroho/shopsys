<?php

namespace SS6\ShopBundle\Model\Grid;

use SS6\ShopBundle\Model\Grid\DataSourceInterface;
use SS6\ShopBundle\Model\Grid\Grid;
use SS6\ShopBundle\Model\Grid\Ordering\GridOrderingService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;
use Twig_Environment;

class GridFactory {

	/**
	 * @var \Symfony\Component\HttpFoundation\RequestStack
	 */
	private $requestStack;

	/**
	 * @var \Symfony\Component\Routing\Router
	 */
	private $router;

	/**
	 * @var \Twig_Environment
	 */
	private $twig;

	/**
	 * @var \SS6\ShopBundle\Model\Grid\Ordering\GridOrderingService
	 */
	private $gridOrderingService;

	/**
	 * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
	 * @param \Symfony\Component\Routing\Router $router
	 * @param \Twig_Environment $twig
	 */
	public function __construct(
		RequestStack $requestStack,
		Router $router,
		Twig_Environment $twig,
		GridOrderingService $gridOrderingService
	) {
		$this->requestStack = $requestStack;
		$this->router = $router;
		$this->twig = $twig;
		$this->gridOrderingService = $gridOrderingService;
	}

	/**
	 * @param string $gridId
	 * @param \SS6\ShopBundle\Model\Grid\DataSourceInterface $dataSource
	 * @return \SS6\ShopBundle\Model\Grid\Grid
	 */
	public function create($gridId, DataSourceInterface $dataSource) {
		return new Grid($gridId, $dataSource, $this->requestStack, $this->router, $this->twig, $this->gridOrderingService);
	}
}
