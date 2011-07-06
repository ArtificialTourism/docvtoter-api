<?php
/**
 * src/controllers/api/username.php
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
 * Username API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class UsernameApiController extends PHPFrame_RESTfulController
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
     * @param string $username      username of user to be returned.
     *
     * @return object      a user object.
     * @since  1.0
     */
    public function get($username)
    {
        if (empty($username)) {
            $username = null;
        }
        
        $id_obj = $this->_getMapper()->getIdObject();
        $id_obj->where('username','=',':username')
        ->params(':username',$username);
        $user = $this->_getMapper()->findOne($id_obj);
        
        if(!isset($user) || $user->id() == 0)
        {
        	$this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }
        
        //return found user
        return $this->handleReturnValue($user);
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
