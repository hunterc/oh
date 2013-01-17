<?php
	class DbUtil{
		public static $lu = "cs4720hwc2d";
		public static $lp = "hwc8591";
		public static $host = "stardock.cs.virginia.edu";
		public static $schema = "cs4720hwc2d";
		
		public static function loginConnection() {
			$db = new mysqli(DbUtil::$host, DbUtil::$lu, DbUtil::$lp, DbUtil::$schema);
			if($db->connect_errno) {
				echo "fail";
				$db->close();
				exit();
			}
			return $db;
		}
	}
?>

