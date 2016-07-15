<?php

class PhpHostConfigWriter extends AbstractHostConfigWriter {
    
    protected $section_writer_class = 'PhpHostConfigSectionWriter';
    protected $option_writer_class = 'PhpConfigOptionWriter';

    public function getContent() {
        $content = explode("\n", $this->getStartOfPhpFile() );
        $content = array_merge($content, parent::getContent());
        return $content;
    }

    private function getStartOfPhpFile() {
        return "<?php\n";
    }
    
}
