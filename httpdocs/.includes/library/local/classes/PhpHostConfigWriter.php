<?php

class PhpHostConfigWriter extends AbstractHostConfigWriter {
    
    protected $section_writer_class = 'PhpHostConfigSectionWriter';
    protected $option_writer_class = 'PhpConfigOptionWriter';
    
}
