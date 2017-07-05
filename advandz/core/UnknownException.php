<?php
/**
 * Format exceptions and errors thrown by PHP for handling in other parts of the app.
 *
 * @package Advandz
 * @subpackage Advandz.libraries
 * @copyright Copyright (c) 2010-2013 Phillips Data, Inc. All Rights Reserved.
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 * @author Cody Phillips <therealclphillips.woop@gmail.com>
 */

namespace Advandz\Core;

use ErrorException;
use Exception;
use ParseError;
use Error;

class UnknownException extends ErrorException
{
    /**
     * Set the exception to be generated by a PHP error set by "set_error_handler".
     *
     * @param  int              $err_no   The error number given by the PHP exception handler
     * @param  string           $err_str  The error string given by the PHP exception handler
     * @param  string           $err_file The file name given by the PHP exception handler
     * @param  int              $err_line The line number given by the PHP exception handler
     * @throws UnknownException Thrown whenever error reporting is set to allow this type of error
     */
    public static function setErrorHandler($err_no, $err_str, $err_file, $err_line)
    {
        // Only report errors if error_reporting is set
        if (error_reporting() === 0) {
            return;
        }

        if (error_reporting() & $err_no) {
            // Throw the exception
            throw new self($err_str, 0, $err_no, $err_file, $err_line);
        }
    }

    /**
     * Report all uncaught exception except if error reporting is turned off.
     *
     * @param mixed $e An exception that has not been caught.
     */
    public static function setExceptionHandler($e)
    {
        // Only report errors if error_reporting is set
        if (error_reporting() === 0) {
            return;
        }

        // Raise error
        if (error_reporting()) {
            $e = new self(htmlentities('Uncaught ' . get_class($e) . ', Message: ' . $e->getMessage()), 0, null, $e->getFile(), $e->getLine());
            
            try {
                Dispatcher::raiseError($e);
            } catch (Exception $e) {
                if (Configure::get('System.debug')) {
                    echo $e->getMessage() . ' on line <strong>' . $e->getLine() .
                        '</strong> in <strong>' . $e->getFile() . "</strong>\n" .
                        '<br />Printing Stack Trace:<br />' . nl2br($e->getTraceAsString());
                } else {
                    echo $e->getMessage();
                }
            }
            
        }
    }

    /**
     * Attempt to raise an error via Advandz\Dispatcher::raiseError() for fatal errors
     * caught using PHP's register_shutdown_function() function.
     */
    public static function setFatalErrorHandler()
    {
        $error = null;

        // This feature only available as of PHP 5.2.0
        if (function_exists('error_get_last')) {
            $error = error_get_last();
        }

        // Only raise error if last registered error was Fatal
        if (!empty($error) && ($error['type'] & E_ERROR) && (error_reporting() & $error['type'])) {
            $e = new self($error['message'], 0, $error['type'], $error['file'], $error['line']);
            
            try {
                Dispatcher::raiseError($e);
            } catch (Exception $e) {
                if (Configure::get('System.debug')) {
                    echo $e->getMessage() . ' on line <strong>' . $e->getLine() .
                        '</strong> in <strong>' . $e->getFile() . "</strong>\n" .
                        '<br />Printing Stack Trace:<br />' . nl2br($e->getTraceAsString());
                } else {
                    echo $e->getMessage();
                }
            }
        }
    }
}
