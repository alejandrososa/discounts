<?php

namespace Kata\Discount\Infrastructure\Ui\Rest;

use Kata\Discount\Application\ProductResponse;
use Kata\Discount\Application\SearchByCriteria\GetProductList;
use Kata\Common\Infrastructure\Bus\BusComponent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function Lambdish\Phunctional\map;

class SearchProductsController
{
    public function __construct(private BusComponent $busComponent)
    {
    }

    #[Route('/products', name: 'product_list', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $orderBy = $request->query->get('order_by');
        $order = $request->query->get('order');
        $limit = $request->query->get('limit');
        $offset = $request->query->get('offset');
        $filters = $request->query->all()['filters'] ?? null;

        $response = $this->busComponent->dispatch(
            new GetProductList(
                null === $filters ? [] : (array)$filters,
                null === $orderBy ? null : (string)$orderBy,
                null === $order ? null : (string)$order,
                null === $limit ? null : (int)$limit,
                null === $offset ? null : (int)$offset
            )
        );

        return new JsonResponse(
            map(
                fn(ProductResponse $product) => [
                    'sku' => $product->sku(),
                    'name' => $product->name(),
                    'category' => $product->category(),
                    'price' => $product->price(),
                ],
                $response->products()
            ),
            Response::HTTP_OK,
            ['Access-Control-Allow-Origin' => '*']
        );
    }
}
