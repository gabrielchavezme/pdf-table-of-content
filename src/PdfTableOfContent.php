<?php

namespace GabrielChavez\PdfTableOfContent;

use Illuminate\Support\Facades\Log;
use GabrielChavez\PdfTableOfContent\Exceptions\FileNotFoundException;
use GabrielChavez\PdfTableOfContent\Exceptions\NoFilesDefinedException;
use TCPDI;

class PdfTableOfContent
{
    /**
     * @var array
     */
    private $documents = [];

    /**
     * Adds a file to merge
     * @param string $file the file to merge
     * @return void
     * @throws FileNotFoundException when the given file does not exist
     */
    public function add(array $document): void
    {
        if (!file_exists($document['file'])) {
            throw new FileNotFoundException($document['file']);
        }

        $this->documents[] = $document;
    }

    /**
     * Checks if the given file is already registered for merging
     * @param string $file the file to check
     * @return bool
     */
    public function contains(string $file): bool
    {
        $found = false;
        foreach ($this->documents as $key => $document) {
            if ($document['file'] == $file) {
                $found = true;
            }
        }

        return $found;
    }

    /**
     * Resets the stored files
     * @return void
     */
    public function reset(): void
    {
        $this->documents = [];
    }

    /**
     * Generates a merged PDF file from the already stored pdf files
     * @param string $outputFilename the file to write to
     * @return array return table of content
     */
    public function merge(string $outputFilename): array
    {
        if (count($this->documents) === 0) {
            throw new NoFilesDefinedException();
        }

        $pdf = new TCPDI();
        $page = 1;

        foreach ($this->documents as $key => $document) {

            if (!is_file($document['file'])) {
                continue;
            }

            $pageCount = $pdf->setSourceFile($document['file']);
            $pdf->Bookmark($document['title'], 0, 0, $page, 'I', array(0, 128, 0));
            $this->documents[$key]['page'] = $page;

            for ($i = 1; $i <= $pageCount; $i++) {

                $pdf->SetPrintHeader(false);
                $pageId = $pdf->ImportPage($i);
                $size = $pdf->getTemplateSize($pageId);
                $pdf->AddPage('P', $size);
                $pdf->useTemplate($pageId);
                $page++;
            }
        }

        $pdf->Output($outputFilename, 'F');

        return $this->documents;
    }
}
