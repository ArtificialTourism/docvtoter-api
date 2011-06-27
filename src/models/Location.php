<?php
class Location extends PHPFrame_PersistentObject
{
	
    public function __construct(array $options=null)
    {
    	$this->addField(
            "address_1",
            null,
            false,
            new PHPFrame_StringFilter(array("max_length"=>100))
        );
        $this->addField(
            "address_2",
            null,
            false,
            new PHPFrame_StringFilter(array("max_length"=>100))
        );
        $this->addField(
            "city",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>50))
        );
        $this->addField(
            "county",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>50))
        );
        $this->addField(
            "post_code",
            null,
            false,
            new PHPFrame_StringFilter(array("max_length"=>10))
        );
        $this->addField(
            "country",
            null,
            true,
            new PHPFrame_StringFilter(array("max_length"=>50))
        );
        $this->addField(
            "lat",
            null,
            true,
            new PHPFrame_FloatFilter()
        );
        $this->addField(
            "long",
            null,
            true,
            new PHPFrame_FloatFilter()
        );
        
        parent::__construct($options);
    }
}
