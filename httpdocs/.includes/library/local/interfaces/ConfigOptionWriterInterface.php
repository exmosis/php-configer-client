<?php

interface ConfigOptionWriterInterface {
   
    public function __construct(ConfigOption $config_option); 
    public function getContent();
    
}
