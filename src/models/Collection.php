<?php
class Collection extends PHPFrame_PersistentObject
{
	public $categories;
	
    public function __construct(array $options=null)
    {
    	$this->addField(
            "name",
            null,
            false,
            new PHPFrame_StringFilter(array("max_length"=>50))
        );
        
        parent::__construct($options);
    }
    
    public function categories($categories)
    {
        if (!is_null($categories)) {
            $this->categories = $categories;
        }
        
        return $this->categories;
    }
}
