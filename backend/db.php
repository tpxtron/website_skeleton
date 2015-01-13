<?php

class db extends PDO {

	public function __construct() {
		// TODO: Set correct database name and credentials
		$dbName = "";
		$dbUser = "root";
		$dbPass = "";

		parent::__construct("mysql:host=localhost;dbname=".$dbName.";charset=utf8",$dbUser,$dbPass);
	}

	public function toString($db,$query,$values) {
		return preg_replace_callback(
			'#\\?#',
			function($match) use ($db, &$values) {
				if (empty($values)) {
					throw new PDOException('not enough values for query');
				}
				$value  = array_shift($values);

				if (is_null($value)) return 'NULL';
				if (true === $value) return 'true';
				if (false === $value) return 'false';
				if (is_numeric($value)) return $value;

				return "'".mysql_escape_string($value)."'";
			},
				$query
			);
	}

}
