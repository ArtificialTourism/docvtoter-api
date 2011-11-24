<?php
/**
 * src/controllers/api/tag.php
 *
 * PHP version 5
 *
 * @category   PHPFrame_Applications
 * @package    DoC
 * @subpackage ApiControllers
 * @author     Will <will@sliderstudio.co.uk>
 * @copyright  2011 Slider Studio Ltd
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link       http://www.sliderstudio.co.uk
 */

/**
 * Tag API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class TagApiController extends PHPFrame_RESTfulController
{
    private $_mapper;

    /**
     * Constructor.
     *
     * @param PHPFrame_Application $app Instance of application.
     *
     * @return void
     * @since  1.0
     */
    public function __construct(PHPFrame_Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Get tag(s).
     *
     * @param int $id       [optional] id of tag to be returned.
     * @param string $name  [optional] filters by name, only valid in combination with type
     * @param string $type  [optional] filters by type, only valid in combination with name
     * @param int $card_id  [optional] filters the tags a card has been tagged with
     * @param int $owner    [optional] filters
     *
     * @return array|object tag object or an array containing tag objects.
     * @since  1.0
     */
    public function get($id=null, $name=null, $type=null, $card_id=null, $owner=null)
    {
        if (empty($id)) {
            $id = null;
        }
        
        if (empty($name)) {
            $name = null;
        }
        
        if (empty($type)) {
            $type = null;
        }

        if (empty($owner)) {
            $owner = null;
        }
        
        if(!is_null($id)) {
            $ret = $this->_getMapper()->findOne(intval($id));
        } elseif(!is_null($name) && !is_null($type)) {
        	$ret = $this->_getMapper()->findByNameType($name, $type);
        } elseif(!is_null($card_id)) {
            $ret = $this->_getMapper()->findByCard($card_id);
        } elseif (!is_null($owner)) {
            $ret = $this->_getMapper()->findByOwner($owner);
        } else {
            $ret = $this->_getMapper()->find();
        }

        //tags not found for some reason, set error status code
        if(!isset($ret)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }

        //return found tags
        return $this->handleReturnValue($ret);
    }
    
    public function post($name, $type='tag', $owner=null)
    {
        if (empty($name)) {
            $name = null;
        }
        
        if (empty($type)) {
            $type = null;
        }

        if (empty($owner)) {
            $owner = null;
        }
        
        //check duplicate name
        $tag = $this->_getMapper()->findByNameType($name, $type);
        
        //if not duplicate, create new tag
        if(!isset($tag) || $tag->id() == 0)
        {
        	$tag = new Tags();
        	$tag->name($name);
        	$tag->type($type);
            if(isset($owner)) $tag->owner($owner);
        	$this->_getMapper()->insert($tag);
        }
        
        return $this->handleReturnValue($tag);
    }
    
    public function put($id, $name=null, $type=null)
    {
        if (empty($id)) {
            $id = null;
        }
    	
    	if (empty($name)) {
            $name = null;
        }
        
        if (empty($type)) {
            $type = null;
        }
        
        //find tag
        if(isset($id)) {
            $tag = $this->_getMapper()->findOne(intval($id));
            
            //tag not found, set error statuscode
            if(!isset($tag) || $tag->id() == 0)
            {
            	$this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
            
            //update tag
            if(isset($name)) $tag->name($name);
            if(isset($type)) $tag->type($type);
            $this->_getMapper()->insert($tag);
        }

        if(!isset($tag))
        {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
        	return;
        }
        
        return $this->handleReturnValue($tag);
    }

    /**
     * Get instance of TagMapper.
     *
     * @return TagMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new TagsMapper($this->db());
        }

        return $this->_mapper;
    }
}
