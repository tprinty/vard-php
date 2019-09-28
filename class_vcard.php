<?php
/*
* Filename: class_vcard.php
* Author: Tom Printy originally by Troy Wolf 
* Description: A class to generate vCards for contact data.
* Original code locatated at https://gist.github.com/raramuridesign/4500527 i
* I have modified this to remove warnings and fix up the constructor of the class 
* so it does not collide with the name of the class.
*/



class vcard {
  private $log;
  public $data;  //array of this vcard's contact data
  public $filename; //filename for download file naming
  public $class; //PUBLIC, PRIVATE, CONFIDENTIAL
  public $revision_date;
  public $card;
   
  /*
  The class constructor. You can set some defaults here if desired.
  */
  function __contruct() {
  
    $this->log = "New vcard() called<br />";
    //TODO: Initialize ALL the variables that could be used	  
    $this->data = array(
      "display_name"=>null
      ,"first_name"=>null
      ,"last_name"=>null
      ,"additional_name"=>null
      ,"name_prefix"=>null
      ,"name_suffix"=>null
      ,"nickname"=>null
      ,"title"=>null
      ,"role"=>null
      ,"department"=>null
      ,"company"=>null
      ,"work_po_box"=>null
      ,"work_extended_address"=>null
      ,"work_address"=>null
      ,"work_city"=>null
      ,"work_state"=>null
      ,"work_postal_code"=>null
      ,"work_country"=>null
      ,"home_po_box"=>null
      ,"home_extended_address"=>null
      ,"home_address"=>null
      ,"home_city"=>null
      ,"home_state"=>null
      ,"home_postal_code"=>null
      ,"home_country"=>null
      ,"office_tel"=>null
      ,"home_tel"=>null
      ,"cell_tel"=>null
      ,"fax_tel"=>null
      ,"pager_tel"=>null
      ,"email1"=>null
      ,"email2"=>null
      ,"url"=>null
      ,"photo"=>null
      ,"birthday"=>null
      ,"timezone"=>null
      ,"sort_string"=>null
      ,"note"=>null
      );
    return true;
  }

  /*
  build() method checks all the values, builds appropriate defaults for
  missing values, generates the vcard data string.
  */  
  function build() {
    $this->log .= "vcard build() called<br />";
    /*
    For many of the values, if they are not passed in, we set defaults or
    build them based on other values.
    */
    if (!$this->class) { $this->class = "PUBLIC"; }
    if (!$this->data['display_name']) {
      $this->data['display_name'] = trim($this->data['first_name']." ".$this->data['last_name']);
    }
    if (!isset($this->data['sort_string'])) { $this->data['sort_string'] = $this->data['last_name']; }
    if (!$this->data['sort_string']) { $this->data['sort_string'] = $this->data['company']; }
    if (!isset($this->data['timezone'])) { $this->data['timezone'] = date("O"); }
    if (!$this->revision_date) { $this->revision_date = date('Y-m-d H:i:s'); }
    
    $this->card = "BEGIN:VCARD\r\n";
    $this->card .= "VERSION:3.0\r\n";
    $this->card .= "CLASS:".$this->class."\r\n";
    $this->card .= "PRODID:-//class_vcard from TroyWolf.com//NONSGML Version 1//EN\r\n";
    $this->card .= "REV:".$this->revision_date."\r\n";
 	$this->card .= "FN:".$this->data['display_name']."\r\n";
    $this->card .= "N:"
      .$this->data['last_name'].";"
      .$this->data['first_name'].";"
      .$this->data['additional_name'].";"
      .$this->data['name_prefix'].";"
      .$this->data['name_suffix']."\r\n";
    if (isset($this->data['nickname'])) { $this->card .= "NICKNAME:".$this->data['nickname']."\r\n"; }
  	if (isset($this->data['title'])) { $this->card .= "TITLE:".$this->data['title']."\r\n"; }
  	if (isset($this->data['company'])) { $this->card .= "ORG:".$this->data['company']; }
  	if (isset($this->data['department'])) { $this->card .= ";".$this->data['department']; }
  	$this->card .= "\r\n";
  	
  	if (isset($this->data['work_po_box'])
    || isset($this->data['work_extended_address'])
    || isset($this->data['work_address'])
    || isset($this->data['work_city'])
    || isset($this->data['work_state'])
    || isset($this->data['work_postal_code'])
    || isset($this->data['work_country'])) {
      $this->card .= "ADR;TYPE=work:";
        if(isset($this->data['work_po_box'])){
		$this->card .=  $this->data['work_po_box'].";";
	}
        $this->card .= $this->data['work_extended_address'].";"
        .$this->data['work_address'].";"
        .$this->data['work_city'].";"
        .$this->data['work_state'].";"
        .$this->data['work_postal_code'].";"
        .$this->data['work_country']."\r\n";
    }
  	if (isset($this->data['home_po_box'])
    || isset($this->data['home_extended_address'])
    || isset($this->data['home_address'])
    || isset($this->data['home_city'])
    || isset($this->data['home_state'])
    || isset($this->data['home_postal_code'])
    || isset($this->data['home_country'])) {
      $this->card .= "ADR;TYPE=home:"
        .$this->data['home_po_box'].";"
        .$this->data['home_extended_address'].";"
        .$this->data['home_address'].";"
        .$this->data['home_city'].";"
        .$this->data['home_state'].";"
        .$this->data['home_postal_code'].";"
        .$this->data['home_country']."\r\n";
    }
    if (isset($this->data['email1'])) { $this->card .= "EMAIL;TYPE=internet,pref:".$this->data['email1']."\r\n"; }
    if (isset($this->data['email2'])) { $this->card .= "EMAIL;TYPE=internet:".$this->data['email2']."\r\n"; }
    if (isset($this->data['office_tel'])) { $this->card .= "TEL;TYPE=work,voice:".$this->data['office_tel']."\r\n"; }
    if (isset($this->data['home_tel'])) { $this->card .= "TEL;TYPE=home,voice:".$this->data['home_tel']."\r\n"; }
    if (isset($this->data['cell_tel'])) { $this->card .= "TEL;TYPE=cell,voice:".$this->data['cell_tel']."\r\n"; }
    if (isset($this->data['fax_tel'])) { $this->card .= "TEL;TYPE=work,fax:".$this->data['fax_tel']."\r\n"; }
    if (isset($this->data['pager_tel'])) { $this->card .= "TEL;TYPE=work,pager:".$this->data['pager_tel']."\r\n"; }
    if (isset($this->data['url'])) { $this->card .= "URL;TYPE=work:".$this->data['url']."\r\n"; }

    if (isset($this->data['photo'])) { 
	
		$image = $this->data['photo'];
		$imagetypes = array (
			IMAGETYPE_JPEG => 'JPEG',
			IMAGETYPE_GIF  => 'GIF',
			IMAGETYPE_PNG  => 'PNG',
			IMAGETYPE_BMP  => 'BMP'
		);
		
		if ($imageinfo = @getimagesize($image) AND isset($imagetypes[$imageinfo[2]])) {
			$photo = base64_encode(file_get_contents($image));
			$type  = $imagetypes[$imageinfo[2]];
			ob_start();
			printf('PHOTO;ENCODING=BASE64;TYPE=%s:%s', $type, $photo);
			$photo_code = ob_get_clean();
		}
		$this->card .= $photo_code . "\r\n"; 
	}

  	if (isset($this->data['birthday'])) { $this->card .= "BDAY:".$this->data['birthday']."\r\n"; }
  	if (isset($this->data['role'])) { $this->card .= "ROLE:".$this->data['role']."\r\n"; }
  	if (isset($this->data['note'])) { $this->card .= "NOTE:".$this->data['note']."\r\n"; }
  	$this->card .= "TZ:".$this->data['timezone']."\r\n";
    $this->card .= "END:VCARD\r\n";
  }
  
  /*
  download() method streams the vcard to the browser client.
  */
  function download() {
    $this->log .= "vcard download() called<br />";
    if (!$this->card) { $this->build(); }
    if (!$this->filename) { $this->filename = trim($this->data['display_name']); }
    $this->filename = str_replace(" ", "_", $this->filename);
  	header("Content-type: text/directory");
  	header("Content-Disposition: attachment; filename=".$this->filename.".vcf");
  	header("Pragma: public");
  	echo $this->card;
    return true;
  }
}
?>
