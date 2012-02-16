<?php
/**
 * src/controllers/api/user.php
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
 * User API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class UserApiController extends PHPFrame_RESTfulController
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
     * Get user.
     *
     * @param int $id      [optional] id of user to be returned.
     * @param int $event   [optional] event_id for event of which all users is to be returned.
     * @param string $username   [optional] username for matching password to id a user.
     * @param string $password   [optional] password for matching username to id a user.
     * @param int $group_id [optional] group_id for matching users with the group id.
     *
     * @return object      a user object or several user objects.
     * @since  1.0
     */
    public function get($id=null, $event=null, $username=null, $password=null, $group_id=null)
    {
        if (empty($id)) {
            $id = null;
        }
        
        if (empty($event)) {
            $event = null;
        }
        
        if (empty($username)) {
            $username = null;
        }
        
        if (empty($password)) {
            $password = null;
        }

        if (empty($group_id)) {
            $group_id = null;
        }
        
        if((!isset($id) || empty($id))
            && (!isset($event) || empty($event))
            && (!isset($username) || !isset($password))
            && (!isset($group_id))
        ) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
            return;
        }
        
        if(isset($id)) {
            $user = $this->_getMapper()->findOne(intval($id));
	        if(!isset($user) || $user->id() == 0)
	        {
	            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
	            return;
	        }
        }
        
        if(isset($event)) {
        	//find all users mapped to event in eventuser table
        	$user = $this->_getMapper()->findByEvent($event);
            if(!isset($user))
            {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
        }
        
        if(isset($username) && isset($password)) {
        	$user = $this->_getMapper()->findByUsername($username);
            if(!isset($user) || $user->id() == 0)
            {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
//                $this->response()->body("Bad Username");
//                return;
                return $this->handleReturnValue("Bad Username");
            }

            //check password match
            if(!$this->_passwords_match($password, $user->password()))
            {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
//                $this->response()->body("Bad Username and Password combination");
//                return;
                return $this->handleReturnValue("Bad Username and Password combination");
            }
        }

        if (isset($group_id)) {
            $id_obj = $this->_getMapper()->getIdObject();
            $id_obj->where('group_id', '=', ':groupid')
                ->params(':groupid', $group_id);
            $users = $this->_getMapper()->find($id_obj);
            if ($users->count() == 0) {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            } else {
                return $this->handleReturnValue($users);
            }
        }

        //return found user
        return $this->handleReturnValue($user);
    }
    
    public function post($username, $group_id, $password=null, $first_name=null, $last_name=null,
        $email=null, $organisation_id=null) 
    {
        if (empty($username)) {
            $username = null;
        }
        
        if (empty($password)) {
            $password = null;
        } else {
        	//hash password
            $salt = $this->app()->crypt()->genRandomPassword(32);
            $crypt = $this->app()->crypt()->encryptPassword($password, $salt);
            $password = $crypt.':'.$salt;
        }
        
        if(!$this->_is_unique($username)) {
            $username = null;
            $this->response()->body("Username not unique");
        }
        
        if (empty($group_id)) {
            $start = null;
        }
        
        if(isset($username) && isset($group_id)) {
        	$user = new User();
        	$user->username($username);
        	$user->group_id($group_id);
        	if(isset($password)) $user->password($password);
        	if(isset($first_name)) $user->first_name($first_name);
        	if(isset($last_name)) $user->last_name($last_name);
        	if(isset($email)) { 
                if($email == "")$email = null;
        		$user->email($email);
        	}
        	if(isset($organisation_id)) $user->organisation_id($organisation_id);
        	
        	$this->_getMapper()->insert($user);
        	
        	$user->owner($user->id());
            $this->_getMapper()->insert($user);
        }
        
        if(!isset($user)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
        	return;
        }        
                
        return $this->handleReturnValue($user);
    }
    
    public function put($id, $username=null, $password=null, $first_name=null, $last_name=null,
        $email=null, $organisation_id=null, $group_id=null)
    {
        if (empty($id)) {
            $id = null;
        }
        
        if(!$this->_is_unique($username)) {
        	$username = null;
        }
        
        if(empty($password)) {
        	$password = null;
        }
    	
        //find user
        if(isset($id)) {
            $user = $this->_getMapper()->findOne(intval($id));
            
            //user not found, set error statuscode
            if(!isset($user) || $user->id() == 0)
            {
            	$this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }

            //hash password if set
            if(isset($password)
                && $password != $user->password()
                && !is_null($password)
            ) {
	            $salt = $this->app()->crypt()->genRandomPassword(32);
	            $crypt = $this->app()->crypt()->encryptPassword($password, $salt);
	            $password = $crypt.':'.$salt;
            }
            
            //update user
            if(isset($password)) $user->password($password);
            if(isset($username)) $user->username($username);
            if(isset($group_id)) $user->group_id($group_id);
            if(isset($first_name)) $user->first_name($first_name);
            if(isset($last_name)) $user->last_name($last_name);
            if(isset($email)) $user->email($email);
            if(isset($organisation_id)) $user->organisation_id($organisation_id);

            $this->_getMapper()->insert($user);
        }

        if(!isset($user))
        {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
        	return;
        }
        
        return $this->handleReturnValue($user);
    }
    
    public function delete($id)
    {
        if (empty($id)) {
            $id = null;
        }
        
        //find user
        if(isset($id)) {
            $user = $this->_getMapper()->findOne(intval($id));
            
            //user not found, set error statuscode
            if(!isset($user) || $user->id() == 0)
            {
                $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
                return;
            }
            
            //delete user
            $this->_getMapper()->delete($user);
        }
    }
    
    private function _is_unique($username) {
    	if(!isset($username) || empty($username)) return false;
    	$id_obj = $this->_getMapper()->getIdObject();
    	$id_obj->where('username','=',':username')
    	->params(':username',$username);
    	$existing = $this->_getMapper()->findOne($id_obj);
    	return !isset($existing) || !$existing->id();
    }
    
    private function _passwords_match($password, $stored_password) {
    	if(empty($stored_password)) {
    		return false;
    	}
    	$parts = split(':',$stored_password);
        if(!isset($parts[1])) {
            return false;
        }
    	$salt = $parts[1];
    	$crypt = $this->app()->crypt()->encryptPassword($password, $salt);
    	
    	$match = $crypt.':'.$salt == $stored_password;
    	
    	return $match;
    }

    /**
     * Get instance of UserMapper.
     *
     * @return UserMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new UserMapper($this->db());
        }

        return $this->_mapper;
    }
}
