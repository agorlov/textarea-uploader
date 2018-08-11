<?php

namespace AG\TextareaUploader\HtmlPage;

use AG\TextareaUploader\HtmlPage;

/**
 * Html-page by Uri (router)
 *
 * Usage example:
 * ```php
 * (new PageByUrl(
 *   $_SERVER['REQUEST_URI'],
 *   [
 *     '/' => new IndexPage(),
 *     '/paste' => new PasteImagePage(new SplFileObject('php://input')),
 *     '/upload' => new UploadFilePage($_FILES, $_SERVER['REQUEST_METHOD'])
 *   ],
 *   new Page404($_SERVER['REQUEST_URI'])
 * ))->print();
 * ```
 * @author Alexandr Gorlov
 */
final class PageByUrl implements HtmlPage {

    private $requestUri;
    private $urlToPageMap = [];
    private $notFound;

    public function __construct(string $requestUri, array $urlToPageMap, HtmlPage $notFound) {
        $this->requestUri = $requestUri;
        $this->urlToPageMap = $urlToPageMap ?? [];
        $this->notFound = $notFound;
    }

    public function print() : string {
        if (array_key_exists($this->requestUri, $this->urlToPageMap)) {
            return $this->urlToPageMap[$this->requestUri]->print();
        } else {
            return $this->notFound->print();
        }
    }
}
