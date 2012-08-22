<?php
 return array (
  'class' => 'CDbConnection',
//  'connectionString' => 'mysql:host=internal-db.s130213.gridserver.com;dbname=db130213_bettime',
//  'username' => 'db130213',
//  'password' => '123123123',
   'connectionString' => 'mysql:host=localhost;dbname=admin_bettime',
   'username' => 'root',
   'password' => 'root',
  'emulatePrepare' => true,
  'charset' => 'utf8',
  'enableParamLogging' => 1,
  'enableProfiling' => 0,
  'schemaCachingDuration' => 0,
  'tablePrefix' => '',
  'initSQLs'=>array('set GLOBAL time_zone="+0:00"','set LOCAL time_zone="+0:00"','set time_zone="+0:00"'),

) ;
?>