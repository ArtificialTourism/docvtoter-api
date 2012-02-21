<?php
class CommentMapper extends PHPFrame_Mapper
{
    private $_include_owner = false;
    private $_user_mapper;

	public function __construct(PHPFrame_Database $db)
    {
        parent::__construct("Comment", $db, "#__comment");

        $this->_user_mapper = new UserMapper($db);
    }
    
    public function find(PHPFrame_IdObject $id_obj=null)
    {
        $collection = parent:: find($id_obj);

        if ($this->_include_owner) {
            foreach ($collection as $comment) {
                $owner = $this->_user_mapper->findOne($comment->owner());
                $comment->ownerUser($owner);
            }
        }

        return $collection;
    }

    public function findOne($id_obj)
    {
        if (is_int($id_obj)){
            $id = $id_obj;
            $id_obj = $this->getIdObject();
            $id_obj->where('id', '=', ':id')
                ->params(':id', $id);
        }
        $collection = $this->find($id_obj);
        $collection->rewind();
        
        return $collection->current();
    }

    public function findByOwner($owner)
    {
        $id_obj = $this->getIdObject();
        $id_obj->where('owner', '=', ':owner')
            ->params(':owner', $owner);

        return $this->find($id_obj);
    }
    
    public function include_owner_object($include_owner=false)
    {
        $this->_include_owner = $include_owner;
    }
}