<?php
/**
 * src/controllers/api/group.php
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
 * Group API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class GroupApiController extends PHPFrame_RESTfulController
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
     * Get group name.
     *
     * @param int $id      id of group the name of which is to be returned.
     *
     * @return string      the group name
     * @since  1.0
     */
    public function get($id)
    {
        if (empty($id)) {
            $id = null;
        }
        
        if(!is_null($id)) {
            $group = $this->_getMapper()->findOne(intval($id));
        }

        //categories not found for some reason, set error status code
        if(!isset($group) || $group->id() == 0) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }

        //return found group name
        return $this->handleReturnValue($group->name());
    }
    
    /**
     * Get instance of GroupMapper.
     *
     * @return GroupMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new PHPFrame_Mapper('PHPFrame_Group', $this->db(), '#__groups');
        }

        return $this->_mapper;
    }
}
