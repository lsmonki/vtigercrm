<?php
//
// phpSysInfo - A PHP System Information Script
// http://phpsysinfo.sourceforge.net/
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// $Id: class.WINNT.inc.php, v1.2 (NT_Service) 2005/04/19 Millennium $
//

class sysinfo {
		var $Win32_Serv_Addr;
		var $Win32_Serv_Port;
		
			
		// Constructor, set IP-address en Port number
		function sysinfo (){
			$this->Win32_Serv_Addr = "127.0.0.1";
			$this->Win32_Serv_Port = 1337;
		
		}
		
		// get our apache SERVER_NAME or vhost
    function vhostname () {
        if (! ($result = getenv('SERVER_NAME'))) {
            $result = 'N.A.';
        }
        return $result;
    }

    // get our canonical hostname
    function chostname () {
	$result = gethostbyaddr(gethostbyname(getenv('COMPUTERNAME')));
       	return $result;
    }

    // get the IP address of our canonical hostname
    function ip_addr () {
        if (!($result = getenv('SERVER_ADDR'))) {
            $result = "N.A.";
        }
        return $result;
    }

    function kernel () {
    	$fp = fsockopen($this->Win32_Serv_Addr, $this->Win32_Serv_Port);
			if (!$fp) {
   			$result = 'N.A.';
			}
			else {
				fputs($fp, "GET kernel\r\n");
				$result = trim(fgets($fp));
   			fclose($fp);
   		}
   		return $result;
    }

    function uptime () {
        $fp = fsockopen($this->Win32_Serv_Addr, $this->Win32_Serv_Port);
	if (!$fp) {
   		$result = 'N.A.';;
	}
	else {
        	fputs($fp, "GET uptime\r\n");
		$result = trim(fgets($fp));
		fclose($fp);
	}
        return $result;
    }

    function users () {
      $fp = fsockopen($this->Win32_Serv_Addr, $this->Win32_Serv_Port);
			if (!$fp) {
   			$result = 'N.A.';
			}
			else {
				fputs($fp, "GET users\r\n");
				$result = trim(fgets($fp));
   			fclose($fp);
   		}
   		return $result;
    }

    function loadavg () {
        $results = array();
      $fp = fsockopen($this->Win32_Serv_Addr, $this->Win32_Serv_Port);
			if ($fp) {
   			fputs($fp, "GET loadavg\r\n");
 				$load = trim(fgets($fp));      
 				for ($counter = 0; $counter < $load; $counter++) {
          $results[$counter] = trim(fgets($fp)) . "%";
      	} 
				fclose($fp);
      }
      return $results;
    }

    function cpu_info () {
    		$results = array();
    		$fp = fsockopen($this->Win32_Serv_Addr, $this->Win32_Serv_Port);
				if ($fp) {
   				fputs($fp, "GET cpu_info\r\n");
        	$results['cpus'] = trim(fgets($fp)); 
        	$results['model']  = trim(fgets($fp));
        	$results['cpuspeed'] = trim(fgets($fp));
        	$results['cache']  = round(trim(fgets($fp)) / 1024) . " KB";
        	$results['bogomips'] = trim(fgets($fp));
        	fclose($fp);
        }       
        return $results;
    }

    function pci () {
     $results = array();
      $fp = fsockopen($this->Win32_Serv_Addr, $this->Win32_Serv_Port);
			if ($fp) {
   			fputs($fp, "GET pci\r\n");
 				$pci = trim(fgets($fp));      
 				for ($counter = 0; $counter < $pci; $counter++) {
          $results[$counter] = trim(fgets($fp));
      	} 
				fclose($fp);
      }
      asort($results);
      return $results;
    }

    function ide () {
      $results = array();
      $fp = fsockopen($this->Win32_Serv_Addr, $this->Win32_Serv_Port);
			if ($fp) {
   			fputs($fp, "GET ide\r\n");
 				$ide = trim(fgets($fp));      
 				for ($counter = 0; $counter < $ide; $counter++) {
          $results[$counter] = array();
          $results[$counter]['model'] = trim(fgets($fp));
      	} 
				fclose($fp);
      }
      asort($results);
      return $results;
    }

    function scsi () {
      $results = array();
      $fp = fsockopen($this->Win32_Serv_Addr, $this->Win32_Serv_Port);
			if ($fp) {
   			fputs($fp, "GET scsi\r\n");
 				$scsi = trim(fgets($fp));      
 				for ($counter = 0; $counter < $scsi; $counter++) {
          $results[$counter] = array();
          $results[$counter]['model'] = trim(fgets($fp));
      	} 
				fclose($fp);
      }
      asort($results);
      return $results;
    }
    
		function usb () {
			$results = array();
      $fp = fsockopen($this->Win32_Serv_Addr, $this->Win32_Serv_Port);
			if ($fp) {
   			fputs($fp, "GET usb\r\n");
 				$usb = trim(fgets($fp));      
 				for ($counter = 0; $counter < $usb; $counter++) {
          $results[$counter] = trim(fgets($fp));
      	} 
				fclose($fp);
      }
      asort($results);
      return $results;
		}
		
    function sbus () {
    	$results = array();
    	$_results[0] = ""; 
    	// TODO. Nothing here yet. Move along.
    	$results = $_results;
    	return $results;
  	} 

    function network () {
      $results = array();
    	$fp = fsockopen($this->Win32_Serv_Addr, $this->Win32_Serv_Port);
			if ($fp) {
   			fputs($fp, "GET network\r\n");
 				$nic = trim(fgets($fp));      
 				for ($counter = 0; $counter < $nic; $counter++) {
          $dev_name = fgets($fp);
     
          $results[$dev_name] = array();

          $results[$dev_name]['rx_bytes'] = trim(fgets($fp));
          $results[$dev_name]['rx_packets'] = trim(fgets($fp));
          $results[$dev_name]['rx_errs'] = trim(fgets($fp));
          $results[$dev_name]['rx_drop'] = trim(fgets($fp));

          $results[$dev_name]['tx_bytes'] = trim(fgets($fp));
          $results[$dev_name]['tx_packets'] = trim(fgets($fp));
          $results[$dev_name]['tx_errs'] = trim(fgets($fp));
          $results[$dev_name]['tx_drop'] = trim(fgets($fp));

          $results[$dev_name]['errs'] = $results[$dev_name]['rx_errs'] + $results[$dev_name]['tx_errs'];
          $results[$dev_name]['drop'] = $results[$dev_name]['rx_drop'] + $results[$dev_name]['tx_drop'];
        } 
				fclose($fp);
      }
    	return $results;
    }

    function memory () {
    		$results['ram'] = array();
    		$results['swap'] = array();
        
        $fp = fsockopen($this->Win32_Serv_Addr, $this->Win32_Serv_Port);
				if ($fp) {
   				fputs($fp, "GET memory\r\n");
        
 					$results['ram']['total']   = trim(fgets($fp)) / 1024;
        	$results['ram']['free']    = trim(fgets($fp)) / 1024;
        	$results['ram']['used']    = $results['ram']['total'] - $results['ram']['free'];
        	$results['ram']['shared']  = 0;
        	$results['ram']['buffers'] = 0;
        	$results['ram']['cached']  = 0;
					$results['ram']['t_used']  = $results['ram']['used'];
        	$results['ram']['t_free']  = $results['ram']['free'];
        	$results['ram']['percent'] = round(($results['ram']['t_used'] * 100) / $results['ram']['total']);
                    
        	$results['swap']['total']   = trim(fgets($fp)) / 1024;
        	$results['swap']['free']    = trim(fgets($fp)) / 1024;
        	$results['swap']['used']    = $results['swap']['total'] - $results['swap']['free'];
        	$results['swap']['percent'] = round(($results['swap']['used'] * 100) / $results['swap']['total']);
        
        	fclose($fp);
        }
        return $results;
    }

    function filesystems () {
    	$results = array();
      $fp = fsockopen($this->Win32_Serv_Addr, $this->Win32_Serv_Port);
			if ($fp) {
   			fputs($fp, "GET filesystems\r\n");
 				$drives = trim(fgets($fp));      
 				for ($counter = 0; $counter < $drives; $counter++) {
          $results[$counter] = array();
					$results[$counter]['disk'] = trim(fgets($fp));
      		$results[$counter]['size'] = trim(fgets($fp)) / 1024;
      		$results[$counter]['free'] = trim(fgets($fp)) / 1024;
      		$results[$counter]['used'] = $results[$counter]['size'] - $results[$counter]['free'];
      		$results[$counter]['percent'] = round(($results[$counter]['used'] * 100) / $results[$counter]['size']) . '%';
      		$results[$counter]['mount'] = trim(fgets($fp));
      		$results[$counter]['fstype'] = trim(fgets($fp));
    		} 
				fclose($fp);
      }
      return $results;  
    }
    
    function distro () {
   		$fp = fsockopen($this->Win32_Serv_Addr, $this->Win32_Serv_Port);
			if (!$fp) {
   			$result = 'N.A.';
			}
			else {
				fputs($fp, "GET distro\r\n");
				$result = trim(fgets($fp));
   			fclose($fp);
   		}
   		return $result;
  	}

  	function distroicon () {   
   		$result = 'xp.gif';
   		return $result;
  	}
}
