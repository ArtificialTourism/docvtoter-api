<?php
class User extends PHPFrame_PersistentObject
{
	
    public function __construct(array $options=null)
    {
    	$this->addField(
            "first_name",
            null,
            true,
            new PHPFrame_StringFilter(array('max_length'=>100))
        );
        $this->addField(
            "last_name",
            null,
            true,
            new PHPFrame_StringFilter(array('max_length'=>100))
        );
        $this->addField(
            "username",
            null,
            false,
            new PHPFrame_StringFilter(array('max_length'=>255))
        );
        $this->addField(
            "email",
            null,
            true,
            new PHPFrame_StringFilter(array('max_length'=>255))
        );
        $this->addField(
            "organisation_id",
            null,
            true,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "role_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        
        parent::__construct($options);
    }
}
