<?php
class Relation extends PHPFrame_PersistentObject
{
	
    public function __construct(array $options=null)
    {
    	$this->addField(
            "parent_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "child_id",
            null,
            false,
            new PHPFrame_IntFilter()
        );
        $this->addField(
            "type",
            null,
            false,
            new PHPFrame_EnumFilter(array(
                 "enums" => array(
                     "issue_action",
                     "issue_issue",
                     "action_action"
                  )
            ))
        );
        $this->addField(
            "weight",
            null,
            false,
            new PHPFrame_FloatFilter()
        );
        
        parent::__construct($options);
    }
}
