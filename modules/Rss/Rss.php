<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
global $calpath;
global $app_strings,$mod_strings;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

require_once('data/CRMEntity.php');
require_once('include/database/PearDatabase.php');
require_once($theme_path."layout_utils.php");
// Require the xmlParser class
require_once('include/feedParser/xmlParser.php');

// Require the feedParser class
require_once('include/feedParser/feedParser.php');

class vtigerRSS extends CRMEntity
{
	var $rss;
	var $rsscache_time = 1200;
	var $rss_object;
	var $rss_title;
	var $rss_link;

	function setRSSUrl($url)
	{
		global $cache_dir;
		//print_r($url);
		/*$this->rss = new lastRSS();
		$this->rss->cache_dir = $cache_dir;
		$this->cache_time = $this->rsscache_time;
		if($this->rss_object = $this->rss->get($url))
		{
			$this->rss_title = $this->rss_object["title"];
			$this->rss_link = $this->rss_object["link"];
			return true;
		}else
		{
			return false;
		}*/
		
		$this->rss = new feedParser();
		// Read in our sample feed file
		$data = @implode("",@file($url));
		// Tell feedParser to parse the data
		$info = $this->rss->parseFeed($data);

		if(isset($info))
		{
			$this->rss_object = $info["channel"];
		}else
		{
			return false;
		}	
		if(isset($this->rss_object))
		{
			$this->rss_title = $this->rss_object["title"];
                        $this->rss_link = $this->rss_object["link"];
			$this->rss_object = $info["item"];
                        return true;
		}else
		{
			return false;
		}
		
	}
	
	/*function getRSSHeadings()
	{
		global $image_path;

		if($this->rss_object)
		{
			$shtml = "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"4\">
        		   <tr>
			        <td class=\"rssPgTitle\"><img src=\"".$image_path."starred.gif\" align=\"absmiddle\">
			            <a href=\"".$this->rss_object[link]."\">".$this->rss_object[title]."</a>
				</td>
        		   </tr>
      			</table>";
			return $shtml;
		}
	}*/
 	 
        function getListViewRSSHtml()
	{
		if(isset($this->rss_object))
        	{
                	$i = 0;
			foreach($this->rss_object as $key=>$item)
                	{
			   $i = $i + 1;	   
			   $shtml .= "<li><a href=\"$item[link]\" class=\"rssNews\" target=\"_blank\">$item[title]</a></li>";
			   if($i == 10)
			   {
				return $shtml;
			   }
	                }
			
			return $shtml;

        	}else
        	{
                	$shtml = "Sorry: It's not possible to reach RSS URL";
        	}

        	//return $shtml;
	}

	function saveRSSUrl($url,$makestarred,$rsscategory)
	{
		global $adb;
		
		if ($url != "")
		{
			$rsstitle = $this->rss_title;
			if($rsstitle == "")
			{
				$rsstitle = $url;
			}
                        $genRssId = $adb->getUniqueID("rss");
			$sSQL = "insert into rss (RSSID,RSSURL,RSSTITLE,RSSTYPE,STARRED,RSSCATEGORY) values (".$genRssId.",'".addslashes($url);
			$sSQL .= "','".addslashes($rsstitle)."',0,".$makestarred.",'".addslashes($rsscategory)."')";
			$result = $adb->query($sSQL);
			if($result)
			{
				 return true;
			}else
			{
				 return false;
			}
		}
	}

	function getStarredRssFolder()
	{
		
		global $adb;
                global $image_path;

                $sSQL = "select * from rss where starred=1";
                $result = $adb->query($sSQL);
                while($allrssrow = $adb->fetch_array($result))
                {
                   $shtml .= "<tr>";
                   $shtml .= "<td width=\"15\">
                   <img src=\"".$image_path."onstar.gif\" align=\"absmiddle\" onMouseOver=\"this.style.cursor='pointer'\" id=\"star-$allrssrow[rssid]\" onclick=\"star('$allrssrow[rssid]','0')\"></td>";
                   $shtml .= "<td class=\"rssTitle\"><a href=\"index.php?module=Rss&action=index&record=$allrssrow[rssid]
\" class=\"rssTitle\">".substr($allrssrow['rsstitle'],0,15)."...</a></td>";
                   $shtml .= "</tr>";
		}
                return $shtml;

	}

	function getCRMRssFeeds()
	{
		global $adb;
                global $image_path;

                $sSQL = "select * from rss where rsstype=1";
                $result = $adb->query($sSQL);
                //$allrssrow = $adb->fetch_array($result);
                while($allrssrow = $adb->fetch_array($result))
                {
                   $shtml .= "<tr>";
		   if($allrssrow["starred"] == 1)
                   {
                      $shtml .= "<td width=\"15\">
                      <img src=\"".$image_path."onstar.gif\" align=\"absmiddle\" onMouseOver=\"this.style.cursor='pointer'\" id=\"star-$allrssrow[rssid]\" onclick=\"star('$allrssrow[rssid]','0')\"></td>";
                   }else
                   {
                      $shtml .= "<td width=\"15\">
                      <img src=\"".$image_path."offstar.gif\" align=\"absmiddle\" onMouseOver=\"this.style.cursor='pointer'\" id=\"star-$allrssrow[rssid]\" onclick=\"star('$allrssrow[rssid]','1')\"></td>";
                   }
                   $shtml .= "<td class=\"rssTitle\"><a href=\"index.php?module=Rss&action=ListView&record=$allrssrow[rssid]
\" class=\"rssTitle\">".$allrssrow[rsstitle]."</a></td>";
                   $shtml .= "</tr>";

                }
                return $shtml;

	}

	function getAllRssFeeds()
	{
		global $adb;
		global $image_path;

		$sSQL = "select * from rss where rsstype <> 1";
		$result = $adb->query($sSQL);
		while($allrssrow = $adb->fetch_array($result))	
		{
		 	$shtml .= "<tr>";
			if($allrssrow["starred"] == 1)
			{
    			   $shtml .= "<td width=\"15\">
			   <img src=\"".$image_path."onstar.gif\" align=\"absmiddle\" onMouseOver=\"this.style.cursor='pointer'\" id=\"star-$allrssrow[rssid]\" onclick=\"star('$allrssrow[rssid]','0')\"></td>";
			}else
			{		
      			   $shtml .= "<td width=\"15\">
			   <img src=\"".$image_path."offstar.gif\" align=\"absmiddle\" onMouseOver=\"this.style.cursor='pointer'\" id=\"star-$allrssrow[rssid]\" onclick=\"star('$allrssrow[rssid]','1')\"></td>";
			}
                   $shtml .= "<td class=\"rssTitle\"><a href=\"index.php?module=Rss&action=ListView&record=$allrssrow[rssid]\" class=\"rssTitle\">".$allrssrow[rsstitle]."</a></td>";
  	           $shtml .= "</tr>";
			
		}
		
		return $shtml;
	}
	
	function getRssUrlfromId($rssid)
	{
		global $adb;
		
		if($rssid != "")
		{
			$sSQL = "select * from rss where rssid=".$rssid;
			$result = $adb->query($sSQL);
			$rssrow = $adb->fetch_array($result);
			
			if(count($rssrow) > 0)
			{
				$rssurl = $rssrow[rssurl];
			}
		}
		return $rssurl;
	}

	function getRSSHeadings($rssid)
        {
                global $image_path;
		global $adb;

                if($rssid != "")
                {
                        $sSQL = "select * from rss where rssid=".$rssid;
                        $result = $adb->query($sSQL);
                        $rssrow = $adb->fetch_array($result);

                        if(count($rssrow) > 0)
                        {
		          $shtml = "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"4\">
	                            <tr>
                                    <td class=\"rssPgTitle\">";
                                    if($rssrow[starred] == 1)
				    {
				    	$shtml .= "<img src=\"".$image_path."starred.gif\" align=\"absmiddle\">";
				    }else
				    {
                                        $shtml .= "<img src=\"".$image_path."unstarred.gif\" align=\"absmiddle\">";
				    }		
                                    $shtml .= "<a href=\"".$this->rss_object[link]."\">  ".$rssrow[rsstitle]."</a>
                                    </td>
                                    </tr>
                                    </table>";

                        }
                }
                return $shtml;


                /*if($this->rss_object)
                {
                        $shtml = "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"4\">
                           <tr>
                                <td class=\"rssPgTitle\"><img src=\"".$image_path."starred.gif\" align=\"absmiddle\">
                                    <a href=\"".$this->rss_object[link]."\">".$this->rss_object[title]."</a>
                                </td>
                           </tr>
                        </table>";
                        return $shtml;
                }*/
        }

	function getTopStarredRSSFeeds()
	{
		global $adb;
                global $image_path;

                $sSQL = "select * from rss where starred=1";
                $result = $adb->query($sSQL);
		$shtml .= "<img src=\"".$image_path."rss.gif\" border=\"0\" align=\"absmiddle\" hspace=\"2\"><a href=\"#\" onclick='window.open(\"index.php?module=Rss&action=Popup\",\"new\",\"width=500,height=300,resizable=1,scrollbars=1\");'>Add New Rss</a>";

                while($allrssrow = $adb->fetch_array($result))
                {
                 $shtml .= "<img src=\"".$image_path."rss.gif\" border=\"0\" align=\"absmiddle\" hspace=\"2\">"; 
		 $shtml .= "<a href=\"index.php?module=Rss&action=ListView&record=$allrssrow[rssid]\" class=\"rssFavLink\">				 ".substr($allrssrow['rsstitle'],0,10)."...</a></img>";
                }
                return $shtml;
	}

	function getStarredRssHTML()
	{
		global $adb;
                global $image_path;
		
                $sSQL = "select * from rss where starred=1";
                $result = $adb->query($sSQL);
                //$shtml = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr>";
		 
                while($allrssrow = $adb->fetch_array($result))
                {
		 //$allrssrow["rssurl"];
		 
		 if($this->setRSSUrl($allrssrow["rssurl"]))
                 {
                        $rss_html = $this->getListViewRSSHtml();
                 }
		$shtml .= "<td width=\"50%\" valign=\"top\">
			   <table class=\"formOuterBorder\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">			       <tr><td class=\"formSecHeader\">";    
		$shtml .= "<img src=\"".$image_path."onstar.gif\" align=\"absmiddle\" onMouseOver=\"this.style.cursor='pointer'\" id=\"star-$allrssrow[rssid]\" onclick=\"star('$allrssrow[rssid]','0')\"> 
			   <span style=\"font-weight:normal\">Today at</span>";
                $shtml .= " <a href=\"$this->rss_link\" target=\"_blank\">".$allrssrow['rsstitle']."</a>";
		$shtml .= "</td></tr>";
		$shtml .= "<tr><td style=\"padding-top:10\"><ul>".$rss_html."</ul></td></tr>";
		if(isset($this->rss_object))
		{
		 	if(count($this->rss_object) > 10)
			{
		  		$shtml .= "<tr><td align=\"right\">
				  	   <a target=\"_BLANK\" href=\"$this->rss_link\">More...</a>
		  		           </td></tr>";
			}
		 }
		 $shtml .= "</table></td><td style=\"width:20;\">&nbsp;&nbsp;</td>";
		 $sreturnhtml[] = $shtml;
		 $shtml = "";
                }
		
		$recordcount = round((count($sreturnhtml))/2);
		$j = $recordcount;
	        for($i=0;$i<$recordcount;$i++)
		{
			$starredhtml .= "<tr>".$sreturnhtml[$i].$sreturnhtml[$j]."</tr>";
			$j = $j + 1;
		}
		$starredhtml  = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">".$starredhtml."</table>";
                
		return $starredhtml;

	}
	
	function getSelectedRssHTML($rssid)
	{
		global $adb;
                global $image_path;
		
                $sSQL = "select * from rss where rssid=".$rssid;
                $result = $adb->query($sSQL);
               // $shtml = "<table width=\"100%\" border=\"0\" cellspacing=\"4\" cellpadding=\"0\">";
 
                while($allrssrow = $adb->fetch_array($result))
                {
		 //$allrssrow["rssurl"];
		 
		 if($this->setRSSUrl($allrssrow["rssurl"]))
                 {
                        $rss_html = $this->getListViewRSSHtml();
                 }
		 $shtml = "<table class=\"formOuterBorder\" width=\"80%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\"><tr><td class=\"formSecHeader\">";  
		if($allrssrow["starred"] == 1)
                {
                $shtml .= "<img src=\"".$image_path."onstar.gif\" align=\"absmiddle\" onMouseOver=\"this.style.cursor='pointer'\" id=\"star-$allrssrow[rssid]\" onclick=\"star('$allrssrow[rssid]','0')\"> <span style=\"font-weight:normal\">Today at</span> ";                                                  }else
                {
        $shtml .= "<img src=\"".$image_path."offstar.gif\" align=\"absmiddle\" onMouseOver=\"this.style.cursor='pointer'\" id=\"star-$allrssrow[rssid]\" onclick=\"star('$allrssrow[rssid]','1')\"> <span style=\"font-weight:normal\">Today at</span> ";
                }
		 $shtml .= " <a href=\"$this->rss_link\" target=\"_blank\">".$allrssrow['rsstitle']."</a>";
		 $shtml .= "</td></tr>";
		 $shtml .= "<tr><td style=\"padding-top:10\"><ul>".$rss_html."</ul></td></tr>";
		 }
		 if(isset($this->rss_object))
                 {
                        if(count($this->rss_object) > 10)
                        {
                                $shtml .= "<tr><td align=\"right\">
                                           <a target=\"_BLANK\" href=\"$this->rss_link\">More...</a>
                                           </td></tr>";
                        }
                 }
		 $shtml .= "</table>";
 		 return $shtml;
	}

	function getRSSCategoryHTML()
	{
	global $adb;
	global $image_path;
	$sSQL = "select * from rsscategory where presence = 1 order by sortorderid";
	$result = $adb->query($sSQL);
//	$categoryrow = $adb->fetch_array($result);
	
	while($categoryrow = $adb->fetch_array($result))
	{
	$shtml .= "<tr>
                <td width=\"15\">
	  	<div align=\"center\"><a href=\"javascript:;\" onClick=\"toggleRSSFolder('".$categoryrow["sortorderid"]."')\"><img id=\"".$categoryrow["sortorderid"]."_toggle\" src=\"".$image_path."plus.gif\" border=\"0\"></a></div></td>
          <td width=\"20\"><div align=\"center\"><img id=\"".$categoryrow["sortorderid"]."_folder\" src=\"".$image_path."rss_folder_cls.gif\"></div></td>
          <td nowrap><a href=\"javascript:;\" onClick=\"toggleRSSFolder('".$categoryrow["sortorderid"]."')\" class=\"rssFolder\">".$categoryrow["rsscategory"]."</a></td>
        </tr>
        <tr>
          <td colspan=\"3\"><div id=\"".$categoryrow["sortorderid"]."_feeds\" style=\"display:none\"><table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" style=\"margin:5 0 0 35\">".$this->getRssFeedsbyCategory($categoryrow["rsscategory"])."</table></div></td>
        </tr>";
	}
	
		return $shtml;
        }

	function getRssFeedsbyCategory($rsscategory)
        {

                global $adb;
                global $image_path;

                $sSQL = "select * from rss where starred <> 1 and rsscategory='".$rsscategory."'";
                $result = $adb->query($sSQL);
                while($allrssrow = $adb->fetch_array($result))
                {
                   $shtml .= "<tr>";
                   $shtml .= "<td width=\"15\">
                   <img src=\"".$image_path."offstar.gif\" align=\"absmiddle\" onMouseOver=\"this.style.cursor='pointer'\" id=\"star-$allrssrow[rssid]\" onclick=\"star('$allrssrow[rssid]','1')\"></td>";
                   $shtml .= "<td class=\"rssTitle\"><a href=\"index.php?module=Rss&action=index&record=$allrssrow[rssid]
\" class=\"rssTitle\">".$allrssrow[rsstitle]."</a></td>";
                   $shtml .= "</tr>";
                }
                return $shtml;

        }

	
	function getRsscategory()
	{
	    global $adb;
            global $image_path;
            $sSQL = "select * from rsscategory where presence = 1 order by sortorderid";
            $result = $adb->query($sSQL);

            while($categoryrow = $adb->fetch_array($result))
            {
		$rsscategories[] = $categoryrow["rsscategory"];
	    }
	    
	    return $rsscategories;
	}
}
?>
