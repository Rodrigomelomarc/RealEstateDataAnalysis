<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Service\DataTreatmentService;
use App\Property;

class ScrapingController extends Controller
{
    private $dataTreatmentService = null;
    private $property = null;

    public function __construct() {
        $this->dataTreatmentService = new DataTreatmentService();
        $this->property             = new Property();
    }

    public function retrieveData(Client $client)
    {
        set_time_limit(0);

        for ($i = 1; $i <= 100; $i++) {
            $crawler = $client->request('GET', env('SCRAP_URL') . "?o=$i" . "&sf=1");

            $crawler->filter(env('PRINCIPAL_NODE'))
                ->each(function (Crawler $propertyNode) {

                    $titleNode          = $this->dataTreatmentService->verifyDataExistence($propertyNode->filter(env('TITLE_NODE')));
                    $titleNode          = $this->dataTreatmentService->sanitizeData($titleNode);

                    $priceNode          = $this->dataTreatmentService->verifyDataExistence($propertyNode->filter(env('PRICE_NODE')));
                    $priceNode          = $this->dataTreatmentService->sanitizeData($priceNode);
                    $priceNode          = $this->dataTreatmentService->sanitizePrice($priceNode);

                    $descriptionNode    = $this->dataTreatmentService->verifyDataExistence($propertyNode->filter(env('DESCRIPTION_NODE')));
                    $descriptionNode    = $this->dataTreatmentService->sanitizeData($descriptionNode);

                    $regionNode         = $this->dataTreatmentService->verifyDataExistence($propertyNode->filter(env('REGION_NODE')));
                    $regionNode         = $this->dataTreatmentService->sanitizeData($regionNode);

                    $categoryNode       = $this->dataTreatmentService->verifyDataExistence($propertyNode->filter(env('CATEGORY_NODE')));
                    $categoryNode       = $this->dataTreatmentService->sanitizeData($categoryNode);

                    $data = [
                        'title' => $titleNode,
                        'price' => $priceNode,
                        'description' => $descriptionNode,
                        'region' => $regionNode,
                        'category' => $categoryNode
                    ];

                    $this->property->create([
                        'title'         => $data['title'],
                        'price'         => $data['price'],
                        'description'   => $data['description'],
                        'region'        => $data['region'],
                        'category'      => $data['category']
                        ]);
                });
        }
    }
}
