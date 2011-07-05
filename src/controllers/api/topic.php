<?php
/**
 * src/controllers/api/topic.php
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
 * Topic API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class TopicApiController extends PHPFrame_RESTfulController
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
     * Get topic(s).
     *
     * @param int $id       [optional] id of topic to be returned.
     *
     * @return array|object topic object or an array containing topic objects.
     * @since  1.0
     */
    public function get($id=null)
    {
        if (empty($id)) {
            $id = null;
        }
        
        if(!is_null($id)) {
            $ret = $this->_getMapper()->findOne(intval($id));
        } else {
            $ret = $this->_getMapper()->find();
        }

        //topics not found for some reason, set error status code
        if(!isset($ret)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }

        //return found topics
        return $this->handleReturnValue($ret);
    }
    
    public function post($name) 
    {
        if (empty($name)) {
            $name = null;
        }
        
        //check duplicate name
        $id_obj = $this->_getMapper()->getIdObject();
        $id_obj->where('name','=',':name')
        ->params(':name',$name);
        $topic = $this->_getMapper()->findOne($id_obj);
        
        //if not duplicate, create new topic
        if(!isset($topic) || $topic->id() == 0)
        {
        	$topic = new Topic();
        	$topic->name($name);
        	$this->_getMapper()->insert($topic);
        }
        
        return $this->handleReturnValue($topic);
    }
    
    public function put($id, $name=null)
    {
        if (empty($id)) {
            $id = null;
        }
    	
    	if (empty($name)) {
            $name = null;
        }
        
        //find topic
        if(isset($id)) {
            $topic = $this->_getMapper()->findOne(intval($id));
            
            //topic not found, set error statuscode
            if(!isset($topic) || $topic->id() == 0)
            {
            	$this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
            
            //update topic
            if(isset($name)) $topic->name($name);
            $this->_getMapper()->insert($topic);
        }

        if(!isset($topic))
        {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
        	return;
        }
        
        return $this->handleReturnValue($topic);
    }

    /**
     * Get instance of TopicMapper.
     *
     * @return TopicMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new PHPFrame_Mapper('topic', $this->db());
        }

        return $this->_mapper;
    }
}
