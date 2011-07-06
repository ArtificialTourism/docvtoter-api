<?php
class Organisation extends PHPFrame_PersistentObject implements PHPFrame_RESTfulObject
{
	private $_child_ids;
	
    public function __construct(array $options=null)
    {
    	$this->addField(
            "name",
            null,
            false,
            new PHPFrame_StringFilter(array('max_length'=>100))
        );
        $this->addField(
            "sector",
            "unspecified",
            false,
            new PHPFrame_EnumFilter(array(
                 "enums" => array(
                     "unspecified",
                     "commercial",
                     "education",
                     "government",
                     "non profit"
                  )
            ))            
        );
        $this->addField(
            "location_id",
            null,
            true,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "parent_id",
            null,
            true,
            new PHPFrame_IntFilter()
        );
        
        parent::__construct($options);
    }
    
    public function child_ids($child_ids=null)
    {
    	if(isset($child_ids)) {
    		$this->_child_ids = $child_ids;
    	}
    	
    	return $this->_child_ids;
    }
    
    public function getRESTfulRepresentation()
    {
        $restful = iterator_to_array($this);
        $restful['child_ids'] = $this->_child_ids;
        return $restful;
    }
}
