<?php

namespace App\Controller;

use App\Service\IptcHeaderKey;
use App\Service\IptcReader;
use App\Service\IptcWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IptcController extends AbstractController
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

    #[Route("/details/{imageName}", "app_details", methods: ["GET", "POST"])]
    public function details(
        Request $request,
        ParameterBagInterface $params,
        string $imageName,
        IptcReader $iptcReader,
        IptcWriter $iptcWriter
    ) {
        if ($request->getMethod() === "GET") {
            return $this->render("details.html.twig", [
                "imagePath" => $params->get("app.images.web_location") . $imageName,
                "comment" => $iptcReader->read($imageName, IptcHeaderKey::COMMENT)
            ]);
        } else {
            $imagePath = $params->get("app.images") . $imageName;
            $iptc = [IptcHeaderKey::COMMENT => $request->request->get('comment', '')];
            $iptcWriter->write($imagePath, $iptc);

            return $this->redirectToRoute("app_details", [
                "imageName" => $imageName
            ]);
        }
    }
}
