PHPNetAppDFM
============

A PHP Class for communicating with the NetApp DFM WSDL API

About:

This class is designed to consume read only data from the NetApp Data Fabric Manager (WSDL) web services API. I wrote it because I could not find any other library that provided this functionality. NetApp do not provide a PHP SDK.
		
Requirements:	

1) This class uses PHP SoapClient so please ensure you have this enabled, you can check this by running phpinfo(); or php -m from the command line.
2) This class requires a local copy of the DFM WSDL file. You can get this by copying the file from your DFM server typically in one of the following locations: c:\Program Files\NetApp\DataFabric Manager\DFM\web\dfm.wdsl or c:\Program Files\NetApp\DataFabric Manager\DFM\misc\dfm.wsdl

Testing:

This has been tested on DFM 5.02 and 5.2 using PHP 5.3.10 on linux (but should work on windows as well)

Usage:
	
$dfm = new NetAppDFM("http://url:port/apis/soap/v1", "username", "password"); // port is usually 8088
$volumes = $dfm->getVolumes();
