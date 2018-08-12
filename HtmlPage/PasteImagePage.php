<?php

namespace AG\TextareaUploader\HtmlPage;

use AG\TextareaUploader\HtmlPage;

/**
 * May be better way:
 * $input = fopen('php://input', 'rb');
 * $file = fopen($filename, 'wb');
 * stream_copy_to_stream($input, $file);
 * fclose($input);
 * fclose($file);
 *
 * @author Alexandr Gorlov
 */
final class PasteImagePage implements HtmlPage {

    /** @var \SplFileObject */
    private $POSTBody;

    private $uploadDir;

    private $webDir;

    /**
     * Constructor.
     *
     * @param \SplFileObject $POSTBody post-body contains binary image
     * @param string $uploadDir directory to store uploaded images
     */
    public function __construct(\SplFileObject $POSTBody, string $uploadDir = './files', string $webDir = '/files') {
        $this->POSTBody = $POSTBody;
        $this->uploadDir = $uploadDir;
        $this->webDir = $webDir;
    }


    public function print() : string {


        $dst = new \SplFileObject($this->uploadDir . "/test.png", 'w+');

        while (!$this->POSTBody->eof()) {
            $dst->fwrite(
                $this->POSTBody->fread(8192)
            );
        }

        if ($dst->ftell() == 0) {
            $fileName = $dst->getPathname();
            $dst = null;
            unlink($fileName);
            throw new \Exception("Image size is 0 bytes. (in POST body)");
        }

        return "{$this->webDir}/test.png";
    }
}


