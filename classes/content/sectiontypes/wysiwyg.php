<?php

class SectionTypeWysiwyg extends SectionType {
    
    function __construct() {
        array_push($this->parameters, new SectionTypeParameter('Content', 'wysiwyg', new CMSTextEditor()));
    }
}


CMSRegistry::register(new SectionTypeWysiwyg());


?>