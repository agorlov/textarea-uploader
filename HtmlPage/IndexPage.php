<?php

namespace AG\TextareaUploader\HtmlPage;

use AG\TextareaUploader\HtmlPage;

/**
 * Index page
 *
 * @author Alexandr Gorlov
 */
final class IndexPage implements HtmlPage {

    private $rootDir;

    public function __construct(string $rootDir)
    {
        $this->rootDir = $rootDir;
    }

    public function print() : string {
        return file_get_contents($this->rootDir . '/main.html');
    }
}
