<?php
/**
 * src/controllers/api/autocomplete.php
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
 * Autocomplete API controller.
 *
 * @category PHPFrame_Applications
 * @package  DoC
 * @author   Will <will@sliderstudio.co.uk>
 * @license  http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link     http://www.sliderstudio.co.uk
 * @since    1.0
 */
class AutocompleteApiController extends PHPFrame_RESTfulController
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
     * Get autocomplete matches.
     *
     * @param int $partial  partial of string to match
     * @param int $for      [Optional] Default value is 'card'. Can be 'card'|'category'|'deck'
     * @param int $exact    [Optional] Default value is 0. Sets to match at beginning of string or any part of string.
     *                      i.e. 1 will match 'ea' in 'earth', but not in 'ocean' whereas 0 would match both 
     *
     * @return array        An array of string matches
     * 
     * @since  1.0
     */
    public function get($partial, $for='card', $exact=0)
    {
        if (empty($partial)) {
            $partial = null;
        }

        if(!in_array($for, array('card','category','deck'))) {
        	$for = 'card';
        }

        if (empty($exact)) {
            $exact = 0;
        }

        if (is_null($partial)) {
            $this->response()->statusCode(PHPFrame_Response::STATUS_BAD_REQUEST);
            return;
        } else {
            $id_obj = $this->_getMapper($for)->getIdObject();
            $partial = $exact ? "$partial%" : "%$partial%";
            $id_obj->where('name', "LIKE", ':partial')
            ->params(':partial',$partial);
            $matches = $this->_getMapper($for)->find($id_obj);
        }
        
        $ret = array();
        
        foreach($matches as $match) {
        	$ret[] = $match->name();
        }

        return $this->handleReturnValue($ret);
    }

    /**
     * Get instance of CardMapper.
     *
     * @return CardMapper
     * @since  1.0
     */
    private function _getMapper($table)
    {
        if (is_null($this->_mapper)) {
            $this->_mapper = new PHPFrame_Mapper($table, $this->db());
        }

        return $this->_mapper;
    }
}
