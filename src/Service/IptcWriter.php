<?php

namespace App\Service;

use App\Model\IptcData;

class IptcWriter
{
    /**
     * Write iptc field to image file
     *
     * $fields must be an array of the following type : `$fields = [IptcHeaderKey::COMMENT => 'Iptc comment']`
     */
    public function write(string $imagePath, IptcData $iptcData)
    {
        $data = '';
        foreach ($iptcData->toArray() as $headerKey => $comment) {
            $headerKey = substr($headerKey, 2);
            $data .= $this->iptc_make_tag(2, $headerKey, $comment);
        }

        $content = iptcembed($data, $imagePath);

        $fp = fopen($imagePath, "wb");
        fwrite($fp, $content);
        fclose($fp);
    }

    /**
     * Convert string to iptc binary
     *
     * @link https://www.php.net/manual/en/function.iptcembed.php
     */
    private function iptc_make_tag($rec, $headerKey, $value)
    {
        $length = strlen($value);
        $retval = chr(0x1C) . chr($rec) . chr($headerKey);

        if ($length < 0x8000) {
            $retval .= chr($length >> 8) .  chr($length & 0xFF);
        } else {
            $retval .= chr(0x80) .
                       chr(0x04) .
                       chr(($length >> 24) & 0xFF) .
                       chr(($length >> 16) & 0xFF) .
                       chr(($length >> 8) & 0xFF) .
                       chr($length & 0xFF);
        }

        return $retval . $value;
    }
}
