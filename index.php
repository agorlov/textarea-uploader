<?php
/**
 * Textarea-uploader example script
 *
 * @author Alexandr Gorlov
 */

require_once 'HtmlPage.php';
require_once 'HtmlPage/PageByUrl.php';
require_once 'HtmlPage/IndexPage.php';
require_once 'HtmlPage/PasteImagePage.php';
require_once 'HtmlPage/Page404.php';
require_once 'HtmlPage/ExceptionPage.php';

use AG\TextareaUploader\HtmlPage\PageByUrl;
use AG\TextareaUploader\HtmlPage\IndexPage;
use AG\TextareaUploader\HtmlPage\PasteImagePage;
use AG\TextareaUploader\HtmlPage\Page404;
use AG\TextareaUploader\HtmlPage\ExceptionPage;


try {
    echo 
        (new PageByUrl(
            $_SERVER['REQUEST_URI'],
            [
                '/' => new IndexPage(__DIR__),
                '/paste' => new PasteImagePage(
                    new SplFileObject('php://input'),
                    __DIR__ . '/files'
                ),
                //'/upload' => new UploadFilePage($_FILES, $_SERVER['REQUEST_METHOD'])
            ],
            new Page404($_SERVER['REQUEST_URI'])
        ))->print();
} catch (Exception $e) {
    echo new ExceptionPage($e);
}