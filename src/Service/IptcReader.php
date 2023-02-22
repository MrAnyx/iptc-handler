<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class IptcReader
{
    public function __construct(
        private ParameterBagInterface $params
    ) {
    }

    public function read(string $imageName, string $headerKey): string | null
    {
        $imagePath = $this->params->get("app.images") . $imageName;
        getimagesize($imagePath, $info);
        if (isset($info['APP13'])) {
            $iptc = iptcparse($info['APP13']);

            if (array_key_exists($headerKey, $iptc)) {
                return $iptc[$headerKey][0];
            }
        }

        return null;
    }
}
