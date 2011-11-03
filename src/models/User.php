<?php
class User extends PHPFrame_User
{
	
    public function __construct(array $options=null)
    {

    	parent::__construct();
    	
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
            "organisation_id",
            null,
            true,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "email",
            null,
            true,
            new PHPFrame_EmailFilter(array("min_length"=>7, "max_length"=>100))
        );
        $this->addField(
            "password",
            null,
            true,
            new PHPFrame_StringFilter()
        );
        
        if(!is_null($options)) {
            $this->bind($options);
        }
    }
}
