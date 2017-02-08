<?php
/**
 * Sets all core configuration settings used throughout the application.
 *
 * @package Advandz
 * @copyright Copyright (c) 2012-2017 CyanDark, Inc. All Rights Reserved.
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 * @author The Advandz Team <team@advandz.com>
 */

//###############################################################################
// System
//###############################################################################
// Enabled debugging (true/false)
Configure::set('System.debug', true);
// Enable benchmarking to output total execution time
Configure::set('System.benchmark', false);
// The view to use as the default structure. To overwrite use
// $this->structure = 'viewfile' in your controller
Configure::set('System.default_structure', 'structure');
// The default controller that will be loaded when no controller is specified.
Configure::set('System.default_controller', 'main');
// The default view directory. If you wish to use another view simultaneously
// then in your controller you would pass the view as the second parameter to
// Controller::render() or View::partial().
Configure::set('System.default_view', 'default');
// View directory to use for all errors that are raised either through uncaught
// exceptions, or explicitly called by invoking Advandz\Dispatcher::raiseError();
Configure::set('System.error_view', 'errors');
// File extension for view files, default is .knife
Configure::set('System.view_ext', '.knife');
// Forward to /404/ when an invalid controller is given
// To change where 404s are sent define a route using Router::route()
Configure::set('System.404_forwarding', true);
// Render views in CLI mode
Configure::set('System.cli_render_views', false);
// Override the default error reporting level after boostrapping
//Configure::errorReporting(0);

//###############################################################################
// Caching
//###############################################################################
// Enable caching (true/false). CACHEDIR must be writable to use file caching.
Configure::set('Caching.on', true);
// The file permissions for cache sub-directories when created
Configure::set('Cache.dir_permissions', 0755);
// File extension for cache files
Configure::set('Caching.ext', '.html');

//###############################################################################
// Knife Template Engine
//###############################################################################
// Enable the Knife template engine (true/false).
Configure::set('Knife.on', true);
// The compiled cached file life-time in seconds.
Configure::set('Knife.ttl', 3600);

//###############################################################################
// Encryption
//###############################################################################
// A binary pseudo-random key hexadecimally encoded.
Configure::set('Encryption.key', '');

//###############################################################################
// Upload
//###############################################################################
// The upload directory.
Configure::set('Upload.upload_dir', '/uploads');

//###############################################################################
// Language
//###############################################################################
// Default language (ISO 639-1/2) to use. If a particular language or string is
// not available in the desired language the default language string will be
// used.
Configure::set('Language.default', 'en_us');
// Set to true to allow keys with no definition to be output, set to false to
// output nothing if the key is not found.
Configure::set('Language.allow_pass_through', false);
