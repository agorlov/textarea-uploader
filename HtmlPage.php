<?php

namespace AG\TextareaUploader;

/**
 * Html-страница
 *
 * @author Alexandr Gorlov
 */
interface HtmlPage {
    /**
     * Вывести html-страницу
     *
     * @return string
     */
    public function print() : string;
}
