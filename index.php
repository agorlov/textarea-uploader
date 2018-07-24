<?php



interface HtmlPage {
    public function print() : string;
}

final class PageByUrl implements HtmlPage {
    
    private $requestUri;
    private $urlToPageMap = [];
    private $notFound;
    
    public function __construct(string $requestUri, array $urlToPageMap = [], HtmlPage $notFound) {
        $this->requestUri = $requestUri;
        $this->urlToPageMap = $urlToPageMap;
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

final class IndexPage implements HtmlPage {
    public function print() : string {
        return file_get_contents('main.html');
    }
}

/**
 * May be better way:
 * $input = fopen('php://input', 'rb');
 * $file = fopen($filename, 'wb');
 * stream_copy_to_stream($input, $file);
 * fclose($input);
 * fclose($file);
 */
final class PasteImagePage implements HtmlPage {
    
    /** @var SplFileObject */
    private $POSTBody;

    private $uploadDir;
    
    /**
     * Constructor.
     * 
     * @param SplFileObject $POSTBody post-body contains binary image
     * @param string $uploadDir directory to store uploaded images
     */
    public function __construct(SplFileObject $POSTBody, string $uploadDir = './files') {
        $this->POSTBody = $POSTBody;       
        $this->uploadDir = $uploadDir;
    }

    
    public function print() : string { 
        // // POST has come
        // if (! isset($_SERVER['HTTP_X_PASTEIMAGE'])) {
        // return "error: bad request, no header X-PASTEIMAGE.";
        // }

     
        $dst = new SplFileObject($this->uploadDir . "/test.png", 'w+');
               
        while (!$this->POSTBody->eof()) {
            $dst->fwrite(
                $this->POSTBody->fread(8192)
            );
        }

        if ($dst->ftell() == 0) {
            $fileName = $dst->
            $dst = null;
            unlink();
            throw new Exception("Image size is 0 bytes. (in POST body)");
        }

        return "ok: {$this->uploadDir}/test.png";
    }
}

final class Page404 implements HtmlPage {
    private $uri;
    public function __construct(string $uri) {
        $this->uri = $uri;
    }
    public function print() : string {
        return "404: There is no HtmlPage obj defined for {$this->uri}";
    }
}

final class ExceptionPage implements HtmlPage {
    private $e;

    public function __construct(Exception $e) {
        $this->e = $e;;
    }

    public function print() : string {
        return "<pre>500\n{$this->e}</pre>";
    }

    public function __toString() {
        return $this->print();
    }
}


try {
    echo 
        (new PageByUrl(
            $_SERVER['REQUEST_URI'],
            [
                '/' => new IndexPage(),
                '/paste' => new PasteImagePage(new SplFileObject('php://input')),
                //'/upload' => new UploadFilePage($_FILES, $_SERVER['REQUEST_METHOD'])
            ],
            new Page404($_SERVER['REQUEST_URI'])
        ))->print();
} catch (Exception $e) {
    echo new ExceptionPage($e);
}