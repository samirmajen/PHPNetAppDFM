<?php
/**
* A PHP Class to communicate with the NetApp Data Fabric Manager (WSDL) web services API
*
* @version 1.0.0
* @author Samir Majen <samirmajen@hotmail.com>
* @copyright 2014 Samir Majen <samirmajen@hotmail.com>
* @license http://choosealicense.com/licenses/mit/ MIT license
* @link https://github.com/samirmajen/PHPNetAppDFM
*/

Class NetAppDFM {
	private $url;
	private $username;
	private $password;
	private $client;
	
	/**
	* Construct the class and connect to the SoapClient
	*/
	public function __construct($url, $username, $password) {
		$this->url			= $url;
		$this->username 	= $username;
		$this->password 	= $password;
		$this->client 		= $this->connect();
		$this->client->__setLocation($this->url);
	}
	
	/* connect to thw WSDL api and create a new SoapClient object */
	private function connect() {
		try {
			$client = new SoapClient('dfm2.wsdl', array('trace' => 1, "cache_wsdl" => 0, "style" => SOAP_RPC, "use" => SOAP_ENCODED, 'login' => $this->username, 'password' => $this->password));
			
			return $client;
		} catch (Exception $e) {
			die("Failed to connect to WSDL with error : {$e}");
		}
	}
	
	/* perform a WSDL action via the SoapClient */
	private function doSoapCall($action, $params = array()) {
		$response = $this->client->__soapCall($action, $params);
		
		return $response;
	}
	
	/* get all requested records such as all luns, all volumes etc. The DFM Tag is a token that is passed between requests and should be cleaned up when finished */
	private function getDfmTagAndAllRecords($action1, $action2, $action3) {
		/* create a temporary list */
		$tag = $this->doSoapCall($action1);
		/* return the records and delete the temporary tag */
		return $this->getDfmRecordsAndCleanUp($tag, $action2, $action3);
	}
	
	/* get an individual DFM record such as a volume by its id or a lun by its id */
	private function getDfmTagAndIndividualRecord($id, $action1, $action2, $action3) {
		$params = array(array( /* must be a double array */
			"ObjectNameOrId" => $id,
		));
		
		$tag = $this->doSoapCall($action1, $params);
		
		return $this->getDfmRecordsAndCleanUp($tag, $action2, $action3);
	}
	
	/* get records and perform a cleanup */
	private function getDfmRecordsAndCleanUp($tag, $action2, $action3) {
		/* prepare parameters */
		$params = array(array( /* must be a double array */
			"Maximum" 	=> $tag->Records,
			"Tag" 		=> "{$tag->Tag}",
		));
		/* get records */
		$records = $this->doSoapCall($action2, $params);
		/* delete the temporary list */
		$params = array(array( /* must be a double array */
			"Tag" => "{$tag->Tag}",
		));
		
		$result = $this->doSoapCall($action3, $params);
		
		return $records;
	}
	
	/* gets all qtrees */
	public function getQtrees() {
		return $this->getDfmTagAndAllRecords("QtreeListInfoIterStart", "QtreeListInfoIterNext", "QtreeListInfoIterEnd");
	}
	
	/* get all volumes */
	public function getVolumes() {
		return $this->getDfmTagAndAllRecords("VolumeListInfoIterStart", "VolumeListInfoIterNext", "VolumeListInfoIterEnd");
	}
	
	/* get an individual volume by its id or name */
	public function getVolume($volume_id) {
		return $this->getDfmTagAndIndividualRecord($volume_id, "VolumeListInfoIterStart", "VolumeListInfoIterNext", "VolumeListInfoIterEnd");
	}
	
	/* get all data protection relationships which includes snapmirrors, snapvaults and qtree snapmirrors */
	public function getDpRelationships() {
		return $this->getDfmTagAndAllRecords("DpRelationshipListInfoIterStart", "DpRelationshipListInfoIterNext", "DpRelationshipListInfoIterEnd");
	}
	
	/* get all groups */
	public function getGroups() {
		return $this->getDfmTagAndAllRecords("GroupListIterStart", "GroupListIterNext", "GroupListIterEnd");
	}
	
	/* get all virtual machines that DFM knows about */
	public function getVirtualMachines() {
		return $this->getDfmTagAndAllRecords("ViVirtualMachineListInfoIterStart", "ViVirtualMachineListInfoIterNext", "ViVirtualMachineListInfoIterEnd");
	}
	
	/* get all datastores that DFM knows about */
	public function getDatastores() {
		return $this->getDfmTagAndAllRecords("ViDatastoreListInfoIterStart", "ViDatastoreListInfoIterNext", "ViDatastoreListInfoIterEnd");
	}
	
	/* get all datacentres that DFM knows about */
	public function getDatacentres() {
		return $this->getDfmTagAndAllRecords("ViDatacenterListInfoIterStart", "ViDatacenterListInfoIterNext", "ViDatacenterListInfoIterEnd");
	}
	
	/* get all hosts */
	public function getHosts() {
		return $this->getDfmTagAndAllRecords("HostListInfoIterStart", "HostListInfoIterNext", "HostListInfoIterEnd");
	}
	
	/* get all snapshots */
	public function getSnapshots() {
		return $this->getDfmTagAndAllRecords("SnapshotListInfoIterStart", "SnapshotListInfoIterNext", "SnapshotListInfoIterEnd");
	}
	
	/* get all cifs */
	public function getCifs() {
		return $this->getDfmTagAndAllRecords("CifsDomainListInfoIterStart", "CifsDomainListInfoIterNext", "CifsDomainListInfoIterEnd");
	}
	
	/* get all luns */
	public function getLuns() {
		return $this->getDfmTagAndAllRecords("LunListInfoIterStart", "LunListInfoIterNext", "LunListInfoIterEnd");
	}
	
	/* get an individual lun by its id or name */
	public function getLun($lun_id) {
		return $this->getDfmTagAndIndividualRecord($lun_id, "LunListInfoIterStart", "LunListInfoIterNext", "LunListInfoIterEnd");
	}
	
	/* get all aggregates */
	public function getAggregates() {
		return $this->getDfmTagAndAllRecords("AggregateListInfoIterStart", "AggregateListInfoIterNext", "AggregateListInfoIterEnd");
	}
}