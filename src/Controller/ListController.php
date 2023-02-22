<?php

namespace App\Controller;

use App\Service\IptcFields;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    #[Route("/", "app_list", methods: ["GET"])]
    public function list(ParameterBagInterface $params)
    {
        $finder = new Finder();
        $finder->files()->in($params->get("app.images"));

        $images = [];
        foreach ($finder as $file) {
            $images[] = [
                "path" => $params->get("app.images.web_location") . $file->getFilename(),
                "filename" => $file->getFilename(),
            ];
        }

        return $this->render("list.html.twig", [
            "images" => $images
        ]);
    }
}
