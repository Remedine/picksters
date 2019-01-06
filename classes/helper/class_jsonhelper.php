<?php
/**
 * Description.
 *
 * @package ${namespace}
 * @Since 1.0.0
 * @author Timothy Hill
 * @link https://executivesuiteit.com
 * @license
 */

namespace ExecutiveSuiteIt\Picksters\Classes\Helper;

class jsonhandler {

		protected static $_messages = array(
			JSON_ERROR_NONE => 'No error has occured',
			JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
			JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
			JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
			JSON_ERROR_SYNTAX => 'Syntax Error',
			JSON_ERROR_UTF8 => ' Malformed UTF-8 characters, possibly incorrectly encoded'
		);

		public static function encode($value, $options = 0) {
			$result = json_encode($value, $options);

			if($result) {
				return $result;
			}
			throw new \RuntimeException(static::$_messages[json_last_error()]);
		}

		public static function decode($json, $assoc = false) {
			$result = json_decode($json, $assoc);

			if($result) {
				return $result;
			}

			throw new \RuntimeException(static::$_messages[json_last_error_msg()]);
		}
}