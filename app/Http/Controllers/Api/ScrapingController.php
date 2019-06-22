<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Service\DataTreatmentService;
use function GuzzleHttp\json_encode;
use function Opis\Closure\serialize;

class ScrapingController extends Controller
{
    public function retrieveData(Client $client)
    {
        $dataTreatmentService = new DataTreatmentService();

        for ($i = 1; $i <= 100; $i++) {
            $crawler = $client->request('GET', env('SCRAP_URL') . "?o=$i" . "&sf=1");

            $crawler->filter(env('PRINCIPAL_NODE'))
                ->each(function (Crawler $propertyNode)
                use ($dataTreatmentService) {

                    $titleNode          = $dataTreatmentService->verifyDataExistence($propertyNode->filter(env('TITLE_NODE')));
                    $titleNode          = $dataTreatmentService->sanitizeData($titleNode);

                    $priceNode          = $dataTreatmentService->verifyDataExistence($propertyNode->filter(env('PRICE_NODE')));
                    $priceNode          = $dataTreatmentService->sanitizeData($priceNode);
                    $priceNode          = $dataTreatmentService->sanitizePrice($priceNode);

                    $descriptionNode    = $dataTreatmentService->verifyDataExistence($propertyNode->filter(env('DESCRIPTION_NODE')));
                    $descriptionNode    = $dataTreatmentService->sanitizeData($descriptionNode);

                    $regionNode         = $dataTreatmentService->verifyDataExistence($propertyNode->filter(env('REGION_NODE')));
                    $regionNode         = $dataTreatmentService->sanitizeData($regionNode);

                    $categoryNode       = $dataTreatmentService->verifyDataExistence($propertyNode->filter(env('CATEGORY_NODE')));
                    $categoryNode       = $dataTreatmentService->sanitizeData($categoryNode);

                    $data = [
                        'Title' => $titleNode,
                        'Price' => $priceNode,
                        'Description' => $descriptionNode,
                        'Region' => $regionNode,
                        'Category' => $categoryNode
                    ];

                    return serialize($data);
                });
        }
    }
}
