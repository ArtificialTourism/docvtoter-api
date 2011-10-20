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
     *
     * @return array|object tag object or an array containing tag objects.
     * @since  1.0
     */
    public function get($id=null, $name=null, $type=null, $card=null)
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
        
        if(isset($id)) {
            $ret = $this->_getMapper()->findOne(intval($id));
        } elseif(isset($name) && isset($type)) {
        	$ret = $this->_getMapper()->findByNameType($name, $type);
        } elseif(isset($card)) {
            $ret = $this->_getMapper()->findByCard($card);
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
    
    public function post($name, $type='tag') 
    {
        if (empty($name)) {
            $name = null;
        }
        
        if (empty($type)) {
            $type = null;
        }
        
        //check duplicate name
        $tag = $this->_getMapper()->findByNameType($name, $type);
        
        //if not duplicate, create new tag
        if(!isset($tag) || $tag->id() == 0)
        {
        	$tag = new Tags();
        	$tag->name($name);
        	$tag->type($type);
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
            if(isset($type)) $tag->name($type);
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
            $this->_mapper = new PHPFrame_Mapper('tags', $this->db());
        }

        return $this->_mapper;
    }
}
