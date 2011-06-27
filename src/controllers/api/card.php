<?php
/**
 * src/controllers/api/card.php
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
 * Card API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class CardApiController extends PHPFrame_RESTfulController
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
     * Get user(s).
     *
     * @param int $id    [Optional] if specified a single card will be returned.
     * @param int $limit [Optional] Default value is 10.
     * @param int $page  [Optional] Default value is 1.
     *
     * @return array|object Either a single card object or an array containing
     *                      card objects.
     * @since  1.0
     */
    public function get($id=null, $limit=10, $page=1)
    {
        if (empty($id)) {
            $id = null;
        }

        if (empty($limit)) {
            $limit = 10;
        }

        if (empty($page)) {
            $page = 1;
        }

        if (!is_null($id)) {
            $ret = $this->_fetchCard($id);
        } else {
            $id_obj = $this->_getCardMapper()->getIdObject();
            $id_obj->limit($limit, ($page-1)*$limit);
            $ret = $this->_getCardMapper()->find($id_obj);
        }

        return $this->handleReturnValue($ret);
    }

//    /**
//     * Save Card passed in POST. If no 'id' is passed in request a new Card
//     * object will be created, otherwise the existing card with a matching 'id'
//     * will be updated.
//     *
//     * @param int    $id            [Optional]
//     *
//     * @return object The card object after saving it.
//     * @since  1.0
//     */
//    public function post(
//        $id=0
//    ) {
//        $id       = filter_var($id, FILTER_VALIDATE_INT);
//        $crypt    = $this->crypt();
//
//        if (!is_int($id) || $id <= 0) {
//            $card = new Card();
//        } else {
//            $card = $this->_fetchCard($id, true);
//        }
//
//        // Save the card object in the database
//        $this->_getUsersMapper()->insert($card);
//
//        return $this->handleReturnValue($card);
//    }

//    /**
//     * Delete card.
//     *
//     * @param int $id The card id.
//     *
//     * @return void
//     * @since  1.0
//     */
//    public function delete($id)
//    {
//        $user = $this->_fetchCard($id, true);
//        $this->_getCardMapper()->delete($card);
//
//        return $this->handleReturnValue(true);
//    }
    
    /**
     * Fetch a card by ID and check read access.
     *
     * @param int  $id The user id.
     *
     * @return User
     * @since  1.0
     */
    private function _fetchCard($id)
    {
        return $this->fetchObj($this->_getCardMapper(), $id);
    }

    /**
     * Get instance of CardMapper.
     *
     * @return CardMapper
     * @since  1.0
     */
    private function _getCardMapper()
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new PHPFrame_Mapper('card', $this->db());
        }

        return $this->_mapper;
    }
}
