<?php
/**
 * src/controllers/api/cardcomments.php
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
 * Cardcomments API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class CardcommentsApiController extends PHPFrame_RESTfulController
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
     * Get card comments.
     *
     * @param int $card_id      id of card for which comments will be returned.
     * @param int $limit        [Optional] Default value is 10.
     * @param int $page         [Optional] Default value is 1.
     * @param boolean $include_owner [Optional] Default value 0, if set to 1 owner user object
     * will be included in comment field owner_user
     *
     * @return array        an array containing comment objects.
     * @since  1.0
     */
    public function get($card_id, $limit=10, $page=1, $include_owner=0)
    {
        if (empty($card_id)) {
            $card_id = null;
        }

        if (empty($limit)) {
            $limit = 10;
        }

        if (empty($page)) {
            $page = 1;
        }

        if ($include_owner == 1) {
            $this->_getMapper()->include_owner_object(true);
        }

        if (!is_null($card_id)) {
            $id_obj = $this->_getMapper()->getIdObject();
            $id_obj->where('card_id','=',':card_id');
            $id_obj->params(':card_id',$card_id);
            $id_obj->limit($limit, ($page-1)*$limit);
            $ret = $this->_getMapper()->find($id_obj);
        }

        $this->_getMapper()->include_owner_object(false);
        
        if(!isset($ret)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_NOT_FOUND);
            return;
        }

        return $this->handleReturnValue($ret);
    }

    /**
     * Get instance of CardMapper.
     *
     * @return CardMapper
     * @since  1.0
     */
    private function _getMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new CommentMapper($this->db());
        }

        return $this->_mapper;
    }
}
