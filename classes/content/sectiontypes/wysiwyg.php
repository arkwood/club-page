<?php

class SectionTypeWysiwyg extends SectionType {
    
    function __construct() {
        array_push($this->parameters, new SectionTypeParameter('Content', 'wysiwyg', new CMSTextEditor()));
        $this->identifier = "wysiwyg";
        $this->label = "WYSIWYG";
        $this->view = 'content/wysiwyg';
    }
}


CMSRegistry::register(new SectionTypeWysiwyg());


?>