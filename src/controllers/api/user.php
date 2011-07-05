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
     * @param int $id      id of user to be returned.
     *
     * @return object      a user object.
     * @since  1.0
     */
    public function get($id)
    {
        if (empty($id)) {
            $id = null;
        }
        
        $user = $this->_getMapper()->findOne(intval($id));

        if(!isset($user) || $user->id() == 0)
        {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }
        
        //return found user
        return $this->handleReturnValue($user);
    }
    
    public function post($username, $role_id, $first_name=null, $last_name=null,
        $email=null, $organisation_id=null) 
    {
        if (empty($username)) {
            $name = null;
        }
        
        if (empty($role_id)) {
            $start = null;
        }
        
        if(isset($username) && isset($role_id)) {
        	$user = new User();
        	$user->username($username);
        	$user->role_id($role_id);
        	if(isset($first_name)) $user->first_name($first_name);
        	if(isset($last_name)) $user->last_name($last_name);
        	if(isset($email)) $user->email($email);
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
    
    public function put($id, $username=null, $first_name=null, $last_name=null,
        $email=null, $organisation_id=null, $role_id=null)
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
            
            //update user
            if(isset($username)) $user->username($username);
            if(isset($role_id)) $user->role_id($role_id);
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

    /**
     * Get instance of UserMapper.
     *
     * @return UserMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new PHPFrame_Mapper('user', $this->db());
        }

        return $this->_mapper;
    }
}
