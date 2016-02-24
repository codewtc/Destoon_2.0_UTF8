<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
class db_mysql {
	var $connid;
	var $querynum = 0;
	var $expires;
	var $cursor = 0;
	var $cache_id = ''; 
	var $cache_file = '';
	var $cache_expires = '';
	var $halt = 0;
	var $result = array();

	function connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect = 0) {
		global $CFG;
		$this->expires = $CFG['db_expires'];
		$func = $pconnect == 1 ? 'mysql_pconnect' : 'mysql_connect';
		if(!$this->connid = $func($dbhost, $dbuser, $dbpw)) {
			$this->halt('Can not connect to MySQL server');
		}
		if($this->version() > '4.1' && $CFG['db_charset']) {
			mysql_query("SET NAMES '".$CFG['db_charset']."'" , $this->connid);
		}
		if($this->version() > '5.0') {
			mysql_query("SET sql_mode=''" , $this->connid);
		}
		if($dbname) {
			if(!mysql_select_db($dbname , $this->connid)) {
				$this->halt('Cannot use database '.$dbname);
			}
		}
		return $this->connid;
	}

	function select_db($dbname) {
		return mysql_select_db($dbname , $this->connid);
	}

	function query($sql , $type = '', $expires = 0, $save_id = false) {
		if($type == 'CACHE' && stristr($sql, 'SELECT')) {
			$this->cursor = 0;
			$this->cache_id = md5($sql);
			$this->result = array();
			$this->cache_expires = $expires ? $expires + mt_rand(-9, 9) : $this->expires;
			return $this->_query($sql);
		}
		if(!$save_id) $this->cache_id = 0;
		$func = $type == 'UNBUFFERED' ? 'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql , $this->connid)) && $this->halt) {
			$this->halt('MySQL Query Error', $sql);
		}
		$this->querynum++;
		return $query;
	}

	function get_one($sql, $type = '', $expires = 0) {
		$query = $this->query($sql, $type, $expires);
		$r = $this->fetch_array($query);
		$this->free_result($query);
		return $r ;
	}
	
	function counter($table, $condition = '', $type = '', $expires = 0) {
		global $DT_PRE;
		$table = strpos($table, $DT_PRE) === false ? $DT_PRE.$table : $table;
		$sql = "SELECT COUNT(*) as num FROM {$table}";
		if($condition) $sql .= " WHERE $condition";
		$r = $this->get_one($sql, $type, $expires);
		return $r ? $r['num'] : 0;
	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return $this->cache_id ? $this->_fetch_array($query) : mysql_fetch_array($query, $result_type);
	}

	function affected_rows() {
		return mysql_affected_rows($this->connid);
	}

	function num_rows($query) {
		return mysql_num_rows($query);
	}

	function num_fields($query) {
		return mysql_num_fields($query);
	}

	function result($query, $row) {
		return @mysql_result($query, $row);
	}

	function free_result($query) {
		return @mysql_free_result($query);
	}

	function insert_id() {
		return mysql_insert_id($this->connid);
	}

	function fetch_row($query) {
		return mysql_fetch_row($query);
	}

	function version() {
		return mysql_get_server_info($this->connid);
	}

	function close() {
		return mysql_close($this->connid);
	}

	function error() {
		return @mysql_error($this->connid);
	}

	function errno() {
		return intval(@mysql_errno($this->connid)) ;
	}

	function halt($message = '', $sql = '')	{
		global $CFG;
		if($message) {
			if($CFG['errlog']) {
				$log = '';
				$log .= "\t\t<query>".$sql."</query>\n";
				$log .= "\t\t<errno>".$this->errno()."</errno>\n";
				$log .= "\t\t<error>".$this->error()."</error>\n";
				$log .= "\t\t<errmsg>".$message."</errmsg>\n";
				log_write($log, 'sql');
			}
		}
		message(($this->halt ? 'MySQL Query:'.$sql.' <br/> MySQL Error:'.$this->error().' MySQL Errno:'.$this->errno().' <br/> Message:'.$message : 'MySQL Message : '.$message));
	}

	function _query($sql) {
		global $DT_TIME;
		$this->cache_file = CACHE_ROOT.'/sql/'.substr($this->cache_id, 0, 2).'/'.$this->cache_id.'.php';
		if(!is_file($this->cache_file) || ($DT_TIME - @filemtime($this->cache_file) > $this->cache_expires)) {
			$tmp = array(); 
			$result = $this->query($sql, '', '', true);
			while($r = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$tmp[] = $r; 
			}
			$this->result = $tmp;
			$this->free_result($result);
			file_put($this->cache_file, "<?php /*".( $DT_TIME+$this->cache_expires)."*/ return ".var_export($this->result, true).";\n?>");
		} else {
		    $this->result = include $this->cache_file;
		}
		return $this->result;
	}

	function _fetch_array($query = array()) {
		if($query) $this->result = $query; 
		if(isset($this->result[$this->cursor])) {
			return $this->result[$this->cursor++];
		} else {
			$this->cursor = $this->cache_id = 0;
			return array();
		}
	}
}
?>