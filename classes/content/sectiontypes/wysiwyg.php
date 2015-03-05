<?php

class SectionTypeWysiwyg extends SectionType {
    
    function __construct() {
        array_push($this->parameters, new SectionTypeParameter('Content', 'wysiwyg', new CMSTextEditor()));
        $this->identifier = "wysiwyg";
        $this->label = "WYSIWYG";
        $this->view = 'views/content/wysiwyg';
        $this->color = '#fca27e';
    }
}


CMSRegistry::register(new SectionTypeWysiwyg());


?>