<?php
/* include core DFM class */
require_once("NetAppDFM_Class.php");
/* instantiate new instance of the DFM object */
$dfm = new NetAppDFM("http://<ip>:<port>/apis/soap/v1", "<username>", "<password>");
/* grab all volumes */
$volumeList = $dfm->getVolumes();

var_dump($volumeList);