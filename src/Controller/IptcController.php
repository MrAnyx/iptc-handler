<?php

namespace App\Controller;

use App\Model\IptcData;
use App\Service\IptcHeaderKey;
use App\Service\IptcReader;
use App\Service\IptcWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        IptcWriter $iptcWriter,
        ValidatorInterface $validator
    ) {
        $filesystem = new Filesystem();

        if (!$filesystem->exists($params->get("app.images") . $imageName)) {
            throw new NotFoundHttpException("Image not found");
        }

        if ($request->getMethod() === "GET") {
            return $this->render("details.html.twig", [
                "imagePath" => $params->get("app.images.web_location") . $imageName,
                "comment" => $iptcReader->read($imageName, IptcHeaderKey::COMMENT),
                "author" => $iptcReader->read($imageName, IptcHeaderKey::AUTHOR),
                "copyright" => $iptcReader->read($imageName, IptcHeaderKey::COPYRIGHT)
            ]);
        } else {
            $this->denyAccessUnlessGranted("ROLE_USER");
            $imagePath = $params->get("app.images") . $imageName;
            $iptcData = new IptcData(
                $request->request->get('comment', ''),
                $request->request->get('author', ''),
                $request->request->get('copyright', ''),
            );

            $errors = $validator->validate($iptcData);

            if (count($errors) > 0) {
                /** @var ConstraintViolation $validationError */
                foreach ($errors as $validationError) {
                    $this->addFlash('error', $validationError->getMessage());
                }
            } else {
                $iptcWriter->write($imagePath, $iptcData);
            }

            return $this->redirectToRoute("app_details", [
                "imageName" => $imageName
            ]);
        }
    }
}
