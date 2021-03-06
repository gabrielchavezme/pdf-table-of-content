<?php

namespace GabrielChavez\PdfTableOfContent\Exceptions;

use Exception;

class FileNotFoundException extends Exception
{
    public function __construct(string $file)
    {
        parent::__construct(sprintf('File "%s" not found', $file));
    }
}
