<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of ImapFetch
 *
 * @author talwin
 */
class ImapFetch {
    
    protected $conn;
    protected $host;
    protected $username;
    protected $password;


    function __construct($params =array()) {
        if(!empty($params)){
            $this->host = $params['host'];
            $this->username = $params['username'];
            $this->password = $params['password'];
        }
    }
    
    function connect(){
        if (function_exists('imap_open')) {
            $this->conn  = @imap_open($this->host, $this->username, $this->password);
            if($this->conn){
                return true;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
        
    }
    function information(){
        $output = array();
        $emails = imap_search($this->conn,' UNSEEN SINCE "16 January 2020"', SE_UID);       
        if($emails){
            
            /* put the newest emails on top */            
            rsort($emails);
            $cnt=0;
            foreach($emails as $email_number) {
                
                /* get information specific to this email */
                $overview = imap_fetch_overview($this->conn,$email_number,0);
                $ov[]=imap_fetch_overview($this->conn,$email_number,0);
                $header = imap_headerinfo($this->conn, $email_number);
                /* get mail structure */	
                $structure = imap_fetchstructure($this->conn, $email_number);				$attachments = array();					
                /* if any attachments found... */	
                if(isset($structure->parts) && count($structure->parts)) 	
                    {			
                        for($i = 0; $i < count($structure->parts); $i++) 	
                                {			
                                 $attachments[$i] = array(	
                                 'is_attachment' => false,		
                                 'filename' => '',		
                                 'name' => '',			
                                 'attachment' => ''			
                                 );		
                                 if($structure->parts[$i]->ifdparameters) 						{	
                                    foreach($structure->parts[$i]->dparameters as $object) 							{	
                                    if(strtolower($object->attribute) == 'filename')
                                        {							
                                    $attachments[$i]['is_attachment'] = true;
									$attachments[$i]['filename'] = $object->value;
                                    }		
                                    }		
                                    }	
                                    if($structure->parts[$i]->ifparameters)
                                        {		
                                    foreach($structure->parts[$i]->parameters as $object) 							{								if(strtolower($object->attribute) == 'name') 								{									$attachments[$i]['is_attachment'] = true;									$attachments[$i]['name'] = $object->value;								}							}						}						if($attachments[$i]['is_attachment']) 						{							$attachments[$i]['attachment'] = imap_fetchbody($this->conn, $email_number, $i+1);							/* 3 = BASE64 encoding */							if($structure->parts[$i]->encoding == 3) 							{ 								$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);							}							/* 4 = QUOTED-PRINTABLE encoding */							elseif($structure->parts[$i]->encoding == 4) 							{ 								$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);							}						}					}				}                
                /* get mail message */
                $message = imap_fetchbody($this->conn,$email_number,1);
                $output[$cnt]['header'] = $header;                $output[$cnt]['attachments'] = $attachments;
                $output[$cnt]['subject']= $overview[0]->subject;
                $output[$cnt]['content'] = $message;
                $cnt++;
            }
            return $output;
            
        }
    }
}
