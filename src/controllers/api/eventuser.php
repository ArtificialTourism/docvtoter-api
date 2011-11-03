<?php
/**
 * src/controllers/api/eventuser.php
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
 * Eventuser API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class EventuserApiController extends PHPFrame_RESTfulController
{
    private $_mapper, $_userMapper;

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
     * Get eventuser.
     *
     * @param int $event_id      id of event.
     * @param int $user_id      id of user.
     *
     * @return array             an array of eventuser objects.
     * @since  1.0
     */
    public function get($event_id, $user_id=null)
    {
        if (empty($event_id)) {
            $event_id = null;
        }
        
        if (empty($user_id)) {
            $user_id = null;
        }
        
        if(!isset($event_id) || !isset($user_id)) {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
            return;
        }
        
        if (isset($event_id)) {
            $ret = $this->_getMapper()->findByEventUser($event_id, $user_id);
            
            if(!isset($ret)) {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
            
            return $this->handleReturnValue($ret);
        }
    }
    
    public function post($event_id, $user_id)
    {
        if (empty($event_id)) {
            $event_id = null;
        }
        
        if (empty($user_id)) {
            $user_id = null;
        }
        
        //verify existence of event and user
        $user_mapper = new PHPFrame_Mapper('user',$this->db());
        $user = $this->_getUserMapper()->findOne(intval($user_id));
        $user = isset($user) && $user->id() != 0;
        $event_mapper = new PHPFrame_Mapper('event',$this->db());
        $event = $event_mapper->findOne(intval($event_id));
        $event = isset($event) && $event->id() != 0;
        if(!$user || !$event) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }
        
        $db = $this->db();
        $params = array(":event_id"=>$event_id, ":user_id"=>$user_id);
        
        //check for existing/duplicate entry
        $id_obj = $this->_getMapper()->getIdObject();
        $id_obj->where('event_id','=',':event_id')
        ->where('user_id','=',':user_id')
        ->params(':event_id',$event_id)
        ->params(':user_id',$user_id);
        $eventuser = $this->_getMapper()->findOne($id_obj);
        
        if(isset($eventuser) && $eventuser->id() > 0)
        {
            return $this->handleReturnValue($eventuser);
        }
        
        $eventuser = new Eventusers();
        $eventuser->event_id($event_id);
        $eventuser->user_id($user_id);  
        $this->_getMapper()->insert($eventuser);

        return $this->handleReturnValue($eventuser);
    }
    
    public function delete($event_id, $user_id)
    {
        if (empty($event_id)) {
            $event_id = null;
        }
        
        if (empty($user_id)) {
            $user_id = null;
        }
        
        if(!isset($event_id) || !isset($user_id))
        {
            $this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
            return;	
        }
        
        //check if entry exists
        $id_obj = $this->_getMapper()->getIdObject();
        $id_obj->where('event_id','=',':event_id')
        ->where('user_id','=',':user_id')
        ->params(':event_id',$event_id)
        ->params(':user_id',$user_id);
        
        $eventuser = $this->_getMapper()->findOne($id_obj);
                
        if(isset($eventuser) && $eventuser->id() > 0)
        {
        	//delete event user mappint
        	$this->_getMapper()->delete($eventuser);
        } else {
            //report error if it entry does not exist
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }
    }
    
    /**
     * Get instance of EventuserMapper.
     *
     * @return EventuserMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new PHPFrame_Mapper('eventusers',$this->db());
        }

        return $this->_mapper;
    }
    
    /**
     * Get instance of UserMapper.
     *
     * @return UserMapper
     * @since  1.0
     */
    private function _getUserMapper()
    {
        if (is_null($this->_userMapper)) {
            $this->_userMapper = new UserMapper($this->db());
        }

        return $this->_userMapper;
    }
}
