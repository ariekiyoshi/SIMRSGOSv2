<?php
	//echo PHP_OS;
	phpinfo();
	//$dir = getcwd();
	//mkdir($dir."/test", 0775, true);
	//chmod($dir."/test", 0775);
	/*try {
		rmdir($dir."/test");
	} catch(\Exception $e) {
		var_dump($e);
	}*/
    /*$params = [
        'query' => 'wahidin',
        'page' => 1
    ]; 
    
    echo http_build_query($params);*/
	
	//echo md5('12345');
	
$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
echo $f->format(1050);
?>