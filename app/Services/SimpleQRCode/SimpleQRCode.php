<?php

namespace App\Services\SimpleQRCode;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SimpleQRCode
{
    public function generateQRcode($url, $image, $url_logo_entreprise)
    {
        if($url_logo_entreprise != 0)
        {
            $qrCode = QrCode::format('png')
                ->size(120)
                ->merge($image, 0.2) // Ajuster la taille du logo
                ->generate($url);

            return base64_encode($qrCode);
        }
        else
        {
            // Créer le QR Code
            $qrCode = QrCode::format('png')
                    ->size(120)
                    ->generate($url);

            return base64_encode($qrCode);
        }
    }
}
