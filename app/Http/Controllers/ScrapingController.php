<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Service\DataTreatmentService;
use Symfony\Component\VarDumper\Cloner\Data;

class ScrapingController extends Controller
{
    public function retrieveData(Client $client)
    {
        $dataTreatmentService = new DataTreatmentService();

        $crawler = $client->request('GET', env('SCRAP_URL'));

        $crawler->filter('.OLXad-list-link')
        ->each(function (Crawler $propertyNode)
        use($dataTreatmentService) {

            $titleNode = $dataTreatmentService->verifyDataExistence($propertyNode->filter('.OLXad-list-title'));
            $titleNode = $dataTreatmentService->sanitizeData($titleNode);

            $priceNode = $dataTreatmentService->verifyDataExistence($propertyNode->filter('.OLXad-list-price'));
            $priceNode = $dataTreatmentService->sanitizeData($priceNode);

            $descriptionNode = $dataTreatmentService->verifyDataExistence($propertyNode->filter('.detail-specific'));
            $descriptionNode = $dataTreatmentService->sanitizeData($descriptionNode);

            $regionNode = $dataTreatmentService->verifyDataExistence($propertyNode->filter('.detail-region'));
            $regionNode = $dataTreatmentService->sanitizeData($regionNode);

            $categoryNode = $dataTreatmentService->verifyDataExistence($propertyNode->filter('.detail-category'));
            $categoryNode = $dataTreatmentService->sanitizeData($categoryNode);

                $data = [
                    'Title' => $titleNode,
                    'Price' => $priceNode,
                    'Description' => $descriptionNode,
                    'Region' => $regionNode,
                    'Category' => $categoryNode
                ];

            var_dump($data);
        });
    }
}
