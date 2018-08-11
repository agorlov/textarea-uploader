<?php

namespace AG\TextareaUploader\HtmlPage;

use AG\TextareaUploader\HtmlPage;

/**
 * 404 Page
 *
 * @author Alexandr Gorlov
 */
final class Page404 implements HtmlPage {
    private $uri;
    public function __construct(string $uri) {
        $this->uri = $uri;
    }
    public function print() : string {
        return "404: There is no HtmlPage obj defined for {$this->uri}";
    }
}