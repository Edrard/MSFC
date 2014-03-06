<pre>
<?
if(!isset($_POST)) {
  die('No direct access allowed');
}

error_reporting(E_ALL & ~E_STRICT);
ini_set("display_errors", 1);
if (file_exists(dirname(__FILE__).'/func_ajax.php')) {
    define('LOCAL_DIR', dirname(__FILE__));
    require(LOCAL_DIR.'/func_ajax.php');
    define('ROOT_DIR', base_dir('ajax'));
}   else{
    define('LOCAL_DIR', '.');
    define('ROOT_DIR', '..');
}

include(ROOT_DIR.'/including/check.php');
require(ROOT_DIR.'/function/cache.php');

$tmp = $t = array();

$cache = new Cache(ROOT_DIR.'/cache/other/');

$t = $cache->get('company_'.$_POST['id'],0);

$t['in_company'] = array();
$t['by_id'] = array();

if(!isset($t['company_names']) or empty($t['company_names'])) {
  $t['company_names'] = array();
}

for($i=1;$i<=$_POST['company'];$i++) {
  parse_str($_POST['sort'.$i], $arr);
  $t['list'.$i] = array();
  if(isset($arr['list']) and !empty($arr['list'])) {
    $t['list'.$i] = $arr['list'];
  }
  if($i <> 0 and isset($arr['list']) and !empty($arr['list'])) {
    $t['in_company'] = array_merge( (array) $t['in_company'], (array) $arr['list']);
    foreach($arr['list'] as $val) {
      $t['by_id'][$val] = $i;
    }
  }
}

$cache->clear('company_'.$_POST['id']);
$cache->set('company_'.$_POST['id'],$t);

/*
foreach($_POST as $a => $t) {
  parse_str( $t , $arr );
  //print_r($arr);
}
print_r($_POST);
*/
print_r($t);
?>
</pre>