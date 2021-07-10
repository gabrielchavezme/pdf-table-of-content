<?php

namespace GabrielChavez\PdfTableOfContent\Tests;

use GabrielChavez\PdfTableOfContent\Exceptions\FileNotFoundException;
use GabrielChavez\PdfTableOfContent\Exceptions\NoFilesDefinedException;
use GabrielChavez\PdfTableOfContent\PdfTableOfContent;
use PHPUnit\Framework\TestCase;
use TCPDI;

class PdfTableOfContentTest extends TestCase
{
    /** @test */
    public function it_fails_on_adding_a_not_existing_file()
    {
        $this->expectException(FileNotFoundException::class);
        $PdfTableOfContent = new PdfTableOfContent();

        $PdfTableOfContent->add([
            'file' => '/foo.pdf',
            'title' => 'Test',
            'id' => 1
        ]);
    }

    /** @test */
    public function it_can_check_if_a_file_was_already_added()
    {
        $PdfTableOfContent = new PdfTableOfContent();
        $file = __DIR__ . '/files/dummy.pdf';

        $this->assertFalse($PdfTableOfContent->contains($file));
        $PdfTableOfContent->add([
            'file' => $file,
            'title' => 'Test',
            'id' => 1
        ]);
        $this->assertTrue($PdfTableOfContent->contains($file));
    }

    /** @test */
    public function it_can_reset_the_files_to_merge()
    {
        $PdfTableOfContent = new PdfTableOfContent();
        $file = __DIR__ . '/files/dummy.pdf';
        $PdfTableOfContent->add([
            'file' => $file,
            'title' => 'Test',
            'id' => 1
        ]);
        $PdfTableOfContent->reset();

        $this->assertFalse($PdfTableOfContent->contains($file));
    }

    /** @test */
    public function it_can_generate_a_merged_file()
    {
        $PdfTableOfContent = new PdfTableOfContent();
        $file = __DIR__ . '/files/dummy.pdf';
        $outputFile = sys_get_temp_dir() . '/output.pdf';
        $outputFile = __DIR__ . '/output.pdf';

        $PdfTableOfContent->add([
            'file' => $file,
            'title' => 'File 1',
            'id' => 1
        ]);
        $PdfTableOfContent->add([
            'file' => $file,
            'title' => 'File 2',
            'id' => 2
        ]);

        $this->assertIsArray($PdfTableOfContent->merge($outputFile));
    }

    /** @test */
    public function it_fails_on_generate_when_no_files_were_added()
    {
        $this->expectException(NoFilesDefinedException::class);

        $PdfTableOfContent = new PdfTableOfContent();
        $PdfTableOfContent->merge('/foo.pdf');
    }

    private static function assertPDFEquals(string $expected, string $actual): void
    {
        self::assertEquals(
            filesize($expected),
            filesize($actual),
            'The file size of the PDF does not equal the file size from the expected output.'
        );

        $pdf = new TCPDI();

        $expectedPageCount = $pdf->setSourceFile($expected);
        $actualPageCount = $pdf->setSourceFile($actual);

        self::assertEquals(
            $expectedPageCount,
            $actualPageCount,
            'The page count of the PDF does not equal the page count from the expected output.'
        );
    }
}
