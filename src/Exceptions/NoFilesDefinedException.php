<?php

namespace GabrielChavez\PdfTableOfContent\Exceptions;

use Exception;

class NoFilesDefinedException extends Exception
{
    public function __construct()
    {
        parent::__construct('No files to merge given');
    }
}
