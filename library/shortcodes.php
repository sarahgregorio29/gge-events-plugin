<?php
/**
 * Controller of shortcode
 *
 * @category Shortcode
 * @package GnGn Recruitment
 * @author Ryo HAYASHI <ryo@gge.co.jp>
 * @version Release: #1067
 */
class Library_Shortcodes
{
    /**
     * These shortcode will be activate
     * @var array $_shortcodes is enable shortcode in shortcodes directory
     */
    private static $shortcodes = array('attendees');

    /**
     * The shortcode will be activation
     */
    public static function activation()
    {
        $basePath = sprintf('%s/shortcodes', dirname(__FILE__));
        foreach (self::$shortcodes as $shortcode) {
            $className = sprintf('Plugin_GnGn_Library_Shortcodes_%s', $shortcode);
            if (!class_exists($className)):
                $filePath = sprintf('%s/%s.php', $basePath, strtolower(str_replace('_', '/', $shortcode)));
                if (!file_exists($filePath)):
                    throw new Exception('Shortcode not found.');
                endif;
                include($filePath);
            endif;
            add_shortcode($shortcode, array(new $className, 'doShortcode'));
        }
    }
}

/**
 * Abstract class of shortcode
 *
 * $this->doShortcode($params) -CALL-> $this->_display($this->_generate($params)) -RETURN-> HTML
 *
 * @category Shortcode
 * @package GnGn Recruitment 
 * @author Ryo HAYASHI <ryo@gge.co.jp>
 * @version Release: #1067
 */
abstract class Plugin_GnGn_Library_Shortcodes_Abstract
{
    /**
     * Return HTML from _display() method to the shortcode by buffering
     *
     * - This method like a Controller in MVC
     *
     * @param array $params is static parameters from the shortcode
     *
     * @return string is HTML from the shortcode class
     */
    final public function doShortcode($params)
    {
        ob_start();
        $this->_display($this->_generate($params));
        return ob_get_clean();
    }

    /**
     * Generating data resource to HTML
     *
     * - This method like a Model in MVC
     *
     * @param array Any variables from doShortcode method
     * @throws Exception
     */
    abstract protected function _generate($params);

    /**
     * Generating HTML to the shortcode
     *
     * - This method like a View in MVC
     *
     * @param array Any variables from _generate method
     * @throws Exception
     */
    abstract protected function _display($params);
}