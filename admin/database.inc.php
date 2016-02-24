<?php
/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
defined('IN_DESTOON') or exit('Access Denied');
require DT_ROOT.'/include/sql.func.php';
$menus = array (
    array('数据库备份', '?file='.$file),
    array('数据库恢复', '?file='.$file.'&action=import'),
    array('字符替换', '?file='.$file.'&action=replace'),
);
$this_forward = '?file='.$file;
switch($action) {
	case 'repair':
		if(!$tables) msg();
		if(is_array($tables)) {
			foreach($tables as $table) {
				$db->query("REPAIR TABLE `$table`");
			}
		} else {
			$db->query("REPAIR TABLE `$tables`");
		}
		dmsg('修复成功', $this_forward);
	break;
	case 'optimize':
		if(!$tables) msg();
		if(is_array($tables)) {
			foreach($tables as $table) {
				$db->query("OPTIMIZE TABLE `$table`");
			}
		} else {
			$db->query("OPTIMIZE TABLE `$tables`");
		}
		dmsg('优化成功', $this_forward);
	break;
	case 'runsql':
		if(trim($sql) == '') {
			msg('SQL语句为空');
		} else {
			$sql = stripslashes($sql);
			sql_execute($sql);
			dmsg('执行成功', '?file='.$file.'&action=import');
		}
	break;
	case 'download':
		$file_ext = file_ext($filename);
		if($file_ext != 'sql') msg('只能下载SQL文件');
		file_down(DT_ROOT.'/file/backup/'.$filename);
	break;
	case 'upload':
		require_once DT_ROOT.'/include/upload.class.php';
		$upload = new upload($_FILES, 'file/backup/', $uploadfile_name, 'sql');
		$upload->adduserid = false;
		if($upload->uploadfile()) dmsg('上传成功', '?file='.$file.'&action=import');
		msg($upload->errmsg);
	break;
	case 'delete':
		 if(is_array($filenames)) {
			 foreach($filenames as $filename) {
				 if(file_ext($filename) == 'sql') @unlink(DT_ROOT.'/file/backup/'.$filename);
			 }
		 } else {
			 if(file_ext($filenames) == 'sql') @unlink(DT_ROOT.'/file/backup/'.$filenames);
		 }
		 dmsg('删除成功', '?file='.$file.'&action=import');
	break;
	case 'replace':
		if($submit) {
			if(!$table || !$fields) msg('请选择字段');
			if($type == 1) {
				if(!$from) msg('请填写查找内容');
				$from = stripslashes($from);
				$to = stripslashes($to);
			} else {
				if(!$add) msg('请填写追加内容');
				$add = stripslashes($add);
			}
			if($conditon) $conditon = stripslashes($conditon);
			$key = '';
			$result = $db->query("SHOW COLUMNS FROM `$table`");
			while($r = $db->fetch_array($result)) {
				if($r['Key'] == 'PRI') {
					$key = $r['Field'];
					break;
				}
			}
			$key or msg('表'.$table.'无主键，无法完成操作');
			$key != $fields or msg('无法完成主键操作');
			$result = $db->query("SELECT `$fields`,`$key` FROM `$table` WHERE 1 $condition");
			while($r = $db->fetch_array($result)) {
				$value = '';
				if($type == 1) {
					$value = str_replace($from, $to, $r[$fields]);
				} else if($type == 2) {
					$value = $add.$r[$fields];
				} else if($type == 3) {
					$value = $r[$fields].$add;
				} else {
					msg();
				}
				$value = addslashes($value);
				$db->query("UPDATE `$table` SET $fields='".$value."' WHERE `$key`='".$r[$key]."'");
			}
			dmsg('操作成功', '?file='.$file.'&action='.$action);
		} else {
			$table_select = '';
			$query = $db->query("SHOW TABLES FROM `".$CFG['db_name']."`");
			while($r = $db->fetch_row($query)) {
				$table = $r[0];
				if(preg_match("/^".$DT_PRE."/i", $table)) {
					$table_select .= '<option value="'.$table.'">'.$table.'</option>';         
				}
			}
			$sql_select = '';
			$sqlfiles = glob(DT_ROOT.'/file/backup/*.sql', GLOB_NOSORT);
			if(is_array($sqlfiles)) {
				foreach($sqlfiles as $id=>$sqlfile)	{
					$tmp = basename($sqlfile);
					if(preg_match("/([a-z0-9_]+_[0-9]{8}_[0-9a-z]{8}_)([0-9]+)\.sql/i", $tmp, $num)) {
						if($num[2] == 1) {
							$sql_select .= '<option value="'.$num[1].'">'.$tmp.'</option>'; 
						}
					}
				}
			}
			include tpl('database_replace');
		}
	break;
	case 'fields':
		(isset($table) && $table) or exit;
		$fields_select = '';
		$result = $db->query("SHOW COLUMNS FROM `$table`");
		while($r = $db->fetch_array($result)) {
			$fields_select .= '<option value="'.$r['Field'].'">'.$r['Field'].'</option>';
		}
		echo '<select name="fields" id="fd"><option value="">选择字段</option>'.$fields_select.'</select>';
		exit;
	break;
	case 'file_replace':
		if(!$file_pre) msg('请选择或者填写备份文件前缀');
		if(!$file_from) msg('请请填写查找内容');
		$fileid = isset($fileid) ? $fileid : 1;
		$filename = $file_pre.$fileid.'.sql';
		$dfile = DT_ROOT.'/file/backup/'.$filename;
		$file_from = urldecode($file_from);
		$file_to = urldecode($file_to);
		if(is_file($dfile)) {
			$sql = file_get_contents($dfile);
			$sql = str_replace($file_from, $file_to, $sql);
			file_put($dfile, $sql);
			$fileid++;
			msg('文件'.$filename.'已经成功替换<br/>请稍候，程序将自动继续...', '?file='.$file.'&action='.$action.'&file_pre='.$file_pre.'&fileid='.$fileid.'&file_from='.urlencode($file_from).'&file_to='.urlencode($file_to));
		} else {
			msg('文件内容替换成功', '?file='.$file.'&action=replace');
		}
	break;
	case 'import':
		if(isset($import)) {
			if(isset($filename) && $filename && file_ext($filename) == 'sql') {
				$dfile = DT_ROOT.'/file/backup/'.$filename;
				if(!is_file($dfile)) msg('文件不存在，请检查');
				$sql = file_get_contents($dfile);
				sql_execute($sql);
				msg($filename.'已经成功导入', '?file='.$file.'&action=import');
			} else {
				$fileid = isset($fileid) ? $fileid : 1;
				$filename = $filepre.$fileid.'.sql';
				$dfile = DT_ROOT.'/file/backup/'.$filename;
				if(is_file($dfile)) {
					$sql = file_get_contents($dfile);
					sql_execute($sql);
					$fileid++;
					msg('文件'.$filename.'已经成功导入<br/>请稍候，程序将自动继续...', '?file='.$file.'&action='.$action.'&filepre='.$filepre.'&fileid='.$fileid.'&import=1');
				} else {
					msg('数据库恢复成功', '?file='.$file.'&action=import');
				}
			}
		} else {		 
			$others = array();
			$sqlfiles = glob(DT_ROOT.'/file/backup/*.sql', GLOB_NOSORT);
			$dsql = $dsqls = $sql = $sqls = array();
			if(is_array($sqlfiles)) {
				$class = 1;
				foreach($sqlfiles as $id=>$sqlfile)	{
					$tmp = basename($sqlfile);
					if(preg_match("/([a-z0-9_]+_[0-9]{8}_[0-9a-z]{8}_)([0-9]+)\.sql/i", $tmp, $num)) {
						$dsql['filename'] = $tmp;
						$dsql['filesize'] = round(filesize($sqlfile)/(1024*1024), 2);
						$dsql['pre'] = $num[1];
						$dsql['number'] = $num[2];
						$dsql['mtime'] = timetodate(filemtime($sqlfile), 5);	if(preg_match("/[a-z0-9_]+_([0-9]{4})([0-9]{2})([0-9]{2})_([0-9]{2})([0-9]{2})([0-9a-z]{4})_/i", $num[1], $tm)) {
							$dsql['btime'] = $tm[1].'-'.$tm[2].'-'.$tm[3].' '.$tm[4].':'.$tm[5];
						} else {
							$dsql['btime'] = $dsql['mtime'];
						}
						if($dsql['number'] == 1) $class = $class  ? 0 : 1;
						$dsql['class'] = $class;
						$dsqls[] = $dsql;
					} else {
						$sql['filename'] = $tmp;
						$sql['filesize'] = round(filesize($sqlfile)/(1024*1024),2);
						$sql['mtime'] = timetodate(filemtime($sqlfile), 5);
						$sqls[] = $sql;
					}
				}
			}
			include tpl('database_import');
		}
	break;
	default:
		if(isset($backup)) {
			$fileid = isset($fileid) ? intval($fileid) : 1;
			if($fileid == 1 && $tables) {
				if(!isset($tables) || !is_array($tables)) msg('请选择需要备份的表');
				$random = date('His', $DT_TIME).mt_rand(10, 99);
				cache_write($_username.'_backup.php', $tables);
			} else {
				if(!$tables = cache_read($_username.'_backup.php')) msg('请选择需要备份的表');
			}
			$sizelimit = $sizelimit ? intval($sizelimit) : 2048;
			$dumpcharset = $sqlcharset ? $sqlcharset : str_replace('-', '', $CFG['charset']);
			$setnames = ($sqlcharset && $db->version() > '4.1' && (!$sqlcompat || $sqlcompat == 'MYSQL41')) ? "SET NAMES '$dumpcharset';\n\n" : '';
			if($db->version() > '4.1') {
				if($sqlcharset) $db->query("SET NAMES '".$sqlcharset."';\n\n");
				if($sqlcompat == 'MYSQL40')	{
					$db->query("SET SQL_MODE='MYSQL40'");
				} else if($sqlcompat == 'MYSQL41') {
					$db->query("SET SQL_MODE=''");
				}
			}
			$sqldump = '';
			$tableid = isset($tableid) ? $tableid - 1 : 0;
			$startfrom = isset($startfrom) ? intval($startfrom) : 0;
			$tablenumber = count($tables);
			for($i = $tableid; $i < $tablenumber && strlen($sqldump) < $sizelimit * 1000; $i++) {
				$sqldump .= sql_dumptable($tables[$i], $startfrom, strlen($sqldump));
				$startfrom = 0;
			}
			if(trim($sqldump)) {
				$sqldump = "# Destoon V".DT_VERSION." R".DT_RELEASE." http://www.destoon.com\n# ".date('Y-m-d H:i:s', $DT_TIME)."\n# --------------------------------------------------------\n\n\n".$sqldump;
				$tableid = $i;
				$filename = $CFG['db_name'].'_'.date('Ymd', $DT_TIME).'_'.$random.'_'.$fileid.'.sql';
				$fileid++;
	
				$bakfile = DT_ROOT.'/file/backup/'.$filename;
				file_put($bakfile, $sqldump);
				msg('SQL文件'.$filename.'备份成功。<br/>请稍候，程序将自动继续...', '?file='.$file.'&sizelimit='.$sizelimit.'&sqlcompat='.$sqlcompat.'&sqlcharset='.$sqlcharset.'&tableid='.$tableid.'&fileid='.$fileid.'&startfrom='.$startrow.'&random='.$random.'&backup=1');
			} else {
			   cache_delete($_username.'_backup.php');
			   file_put(DT_ROOT.'/file/backup/backup.dat', $DT_TIME);
			   msg('数据库备份成功，文件保存于 file/backup/ 目录<br/>建议尽快下载至本地，然后从服务器上删除', $this_forward, 10);
			}
		} else {
			$dtables = $tables = array();
			$i = $j = $dtotalsize = $totalsize = 0;
			$query = $db->query("SHOW TABLES FROM `".$CFG['db_name']."`");
			while($rr = $db->fetch_row($query)) {
				$r = $db->get_one("SHOW TABLE STATUS FROM `".$CFG['db_name']."` LIKE '".$rr[0]."'");
				if(preg_match('/^'.$DT_PRE.'/', $rr[0])) {
					$dtables[$i]['name'] = $r['Name'];
					$dtables[$i]['rows'] = $r['Rows'];
					$dtables[$i]['size'] = round($r['Data_length']/1024/1024, 2);
					$dtables[$i]['auto'] = $r['Auto_increment'];
					$dtables[$i]['updatetime'] = $r['Update_time'];
					$dtables[$i]['note'] = $r['Comment'];
					$dtotalsize += $r['Data_length'];
					$i++;
				} else {
					$tables[$j]['name'] = $r['Name'];
					$tables[$j]['rows'] = $r['Rows'];
					$tables[$j]['size'] = round($r['Data_length']/1024/1024, 2);
					$tables[$j]['auto'] = $r['Auto_increment'];
					$tables[$j]['updatetime'] = $r['Update_time'];
					$tables[$j]['note'] = $r['Comment'];
					$totalsize += $r['Data_length'];
					$j++;
				}
			}
			$dtotalsize = round($dtotalsize/1024/1024, 2);
			$totalsize = round($totalsize/1024/1024, 2);
			include tpl('database');
		}
	break;
}
?>