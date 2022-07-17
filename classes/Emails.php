<?php

class Emails{
	
	private $siteEmailAddress;
	private $siteTeam;
	private $headers;
	private $pathtoemail = array("../emails/", "emails/");
	
	  public function __construct(){
		//headers
		$this->headers  = "MIME-Version: 1.0\r\n";
		$this->headers .= "Content-type: text/html; charset=UTF-8\r\n";
		//get App Email Address
		$appObj = new App();
		$this->siteEmailAddress = $appObj->getAppEmail();
		$this->siteTeam         = $appObj->getAppName()." Admin";
	  }
	  
	 public function process($case = null, $array = null){
		 if(!empty($case)){
			switch($case){
				//contact us
				case "contactus":
				$array["email_to"] = $this->siteEmailAddress;
				$array["subject"]  = "New Message From Contact Form";
				$array["body"]     = $array["message"];
				$this->headers    .= "From: ".$array["name"]." <".$array["email"].">\r\n";
				break;
				//forgot password
				case "forgotpsw":
				$array["email_to"] = $array["login"];
				$array["subject"]  = "New Password Request";
				// add url to the array
				$link   		   = "<a href=\"".SITE_URL."/xlogin.php\">";
				$link  			  .= "Login here </a>";
                $array["link"] 	   = $link;
				$array["body"]     = $this->fetchEmail($case, $array);
				$this->headers    .= "From: ".$this->siteTeam." <".$this->siteEmailAddress.">\r\n";
				break;
				//activate user account
				case "newmember":
				$array["subject"]  = "Registration successful";
				$array["email_to"] = $array["email"];
				// add url to the array
				$link  			   = "<a href=\"".SITE_URL."/activate.php?activate-code=";
				$link 			  .= $array['hash'];
				$link 			  .= "\">";
				$link 			  .= SITE_URL."/activate.php?activate-code=";
				$link 			  .= $array['hash'];
				$link 			  .= "</a>";
                $array["link"]     = $link;
				$array["body"]     = $this->fetchEmail($case, $array);
				$this->headers    .= "From: ".$this->siteTeam." <".$this->siteEmailAddress.">\r\n";
				break;
			}
			
			$sendMail = mail($array["email_to"], $array["subject"], $array["body"], $this->headers);
			if($sendMail){
				return true;
			} else {
				return false;
			}
			
		 }
	 }
	
	public function fetchEmail($case = null, $array = null) {
	
		if (!empty($case)) {
			
			if (!empty($array)) {			
				foreach($array as $key => $value) {
					${$key} = $value;
				}			
			}
			
			foreach($this->pathtoemail as $emailpath){
				if(file_exists($emailpath)){
					ob_start();
					require_once($emailpath.$case.".php");
					$out = ob_get_clean();
					return $this->wrapEmail($out);
				}
			}		
		}
	
    }
    
	public function wrapEmail($content = null) {
		if (!empty($content)) {
			return "<div style=\"font-family:Arial,Verdana,Sans-serif;font-size:12px;color:#333;line-height:21px;\">{$content}</div>";
		}
	}
	
	
	
}