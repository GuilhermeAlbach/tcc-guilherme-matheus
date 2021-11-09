<?php


namespace App;

use Dompdf\Options;
use Dompdf\Dompdf;


class PDF
{
    private $pdf;

    public function __construct()
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
		$pdfOptions->setChroot(getcwd());
		$pdfOptions->setIsRemoteEnabled(true);

        $this->dompdf = new Dompdf();

        $this->dompdf->setOptions($pdfOptions);
        $this->dompdf->setPaper('A4', 'portrait');
    }

    public function exibir($html, $arquivo='')
    {
        if ($arquivo==''){
            $arquivo = uniqid() . '.pdf';
        }

        $this->dompdf->loadHtml($html);
        $this->dompdf->render();
        $this->dompdf->stream($arquivo, [
            "Attachment" => false
        ]);

    }

}
