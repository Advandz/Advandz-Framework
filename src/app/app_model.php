<?php
/**
 * The parent model for the application.
 * 
 * @package Advandz
 * @subpackage Advandz.app
 * @copyright Copyright (c) 2012-2017 CyanDark, Inc. All Rights Reserved.
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 * @author The Advandz Team <team@advandz.com>
 */
class AppModel extends Model {
    /**
     * The main app model constructor.
     */
    public function __construct($db_info = null) {
        // Load Components
        Loader::loadComponents($this, ["Record"]);
    }

	#
	# TODO: Define any methods that you would like to use in any of your other
	# models that extend this class.
	#
}
?>