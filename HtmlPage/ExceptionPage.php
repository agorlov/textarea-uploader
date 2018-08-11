<?php
namespace AG\TextareaUploader\HtmlPage;

use AG\TextareaUploader\HtmlPage;

/**
 * Exception Page - typical HTTP 500 error
 *
 * @author Alexandr Gorlov
 */
final class ExceptionPage implements HtmlPage {
    private $e;

    public function __construct(\Exception $e) {
        $this->e = $e;;
    }

    public function print() : string {
        return "<pre>500\n{$this->e}</pre>";
    }

    public function __toString() {
        return $this->print();
    }
}