<?php
/**
 * src/controllers/api/event.php
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
 * Event API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class EventApiController extends PHPFrame_RESTfulController
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
     * Get event. Although parameters are optional at least one
     * should be specified or a bad request status code will be sent.
     *
     * @param int $id      [optional] id of event to be returned.
     * @param int $user    [optional] id of a user, if specified all
     * events the user attended will be returned
     * @param int $owner   [optional] owner of the event, if specified 
     * events owned by the user will be returned
     *
     * @return object      an event object.
     * @since  1.0
     */
    public function get($id = null, $user = null, $owner = null)
    {
        if (empty($id)) {
            $id = null;
        }
        
        if (empty($user)) {
            $user = null;
        }

        if (empty($owner)) {
            $owner = null;
        }
        
        if((!isset($id) || empty($id)) && (!isset($user) || empty($user))
           && (!isset($owner) || empty($owner))) {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
            return;
        }
        
        if(isset($id)) {
            $ret = $this->_getMapper()->findOne(intval($id));	

            if(!isset($ret) || $ret->id() == 0)
            {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
        }
        
        if(isset($user)) {
        	$ret = $this->_getMapper()->findByUser($user);
        	
            if(!isset($ret))
            {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
        }

        if (isset($owner)) {
            $ret = $this->_getMapper()->findByOwner($owner);
            
            if(!isset($ret))
            {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
        }

        //return found event
        return $this->handleReturnValue($ret);
    }
    
    public function post($name, $start, $description=null, $summary=null, $end=null,
        $location_id=null, $allow_anon=null, $auto_publish=null, $auto_close=null,
        $owner=null, $private=null, $password=null
    ) 
    {
        if (empty($name)) {
            $name = null;
        }
        
        if (empty($start)) {
            $start = null;
        }
        
        if (empty($owner)) {
            $owner = null;
        }
        
        if(isset($name) && isset($start)) {
        	$event = new Event();
        	$event->name($name);
        	$event->start($start);
        	if(isset($description)) $event->description($description);
        	if(isset($summary)) $event->summary($summary);
        	if(isset($end)) $event->end($end);
        	if(isset($location_id)) $event->location_id($location_id);
        	if(isset($allow_anon)) $event->allow_anon($allow_anon);
        	if(isset($auto_publish)) $event->auto_publish($auto_publish);
        	if(isset($auto_close)) $event->auto_close($auto_close);
        	if(isset($private)) $event->private($private);
        	if(isset($password)) $event->password($password);
        	if(isset($owner)) $event->owner($owner);
        	
        	$this->_getMapper()->insert($event);
        }
        
        if(!isset($event)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
        	return;
        }        
                
        return $this->handleReturnValue($event);
    }
    
    public function put($id, $name=null, $description=null, $summary=null, $end=null,
        $location_id=null, $allow_anon=null, $auto_publish=null, $auto_close=null,
        $private=null, $password=null)
    {
        if (empty($id)) {
            $id = null;
        }
    	
        //find event
        if(isset($id)) {
            $event = $this->_getMapper()->findOne(intval($id));
            
            //event not found, set error statuscode
            if(!isset($event) || $event->id() == 0)
            {
            	$this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
            
            //update event
            if(isset($name)) $event->name($name);
            if(isset($description)) $event->description($description);
            if(isset($summary)) $event->summary($summary);
            if(isset($end)) $event->end($end);
            if(isset($location_id)) $event->location_id($location_id);
            if(isset($allow_anon)) $event->allow_anon($allow_anon);
            if(isset($auto_publish)) $event->auto_publish($auto_publish);
            if(isset($auto_close)) $event->auto_close($auto_close);
            if(isset($password)) $event->password($password);
            if(isset($private)) $event->private($private);
            $this->_getMapper()->insert($event);
        }

        if(!isset($event))
        {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
        	return;
        }
        
        return $this->handleReturnValue($event);
    }
    
    public function delete($id)
    {
        if (empty($id)) {
            $id = null;
        }
        
        //find event
        if(isset($id)) {
            $event = $this->_getMapper()->findOne(intval($id));
            
            //event not found, set error statuscode
            if(!isset($event) || $event->id() == 0)
            {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
            
            //delete event
            $this->_getMapper()->delete($event);
        }
    }

    /**
     * Get instance of EventMapper.
     *
     * @return EventMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new EventMapper($this->db());
        }

        return $this->_mapper;
    }
}
