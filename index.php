<?php
require_once('PortalConfig.php');
require_once('functions.php');
require_once('language/en_us.lang.php');
require_once('nusoap/lib/nusoap.php');
$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : $Proxy_Host;
$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : $Proxy_Port;
$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : $Proxy_Username;
$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : $Proxy_Password;
global $client;
global $Server_Path;
$client = new soapclient($Server_Path."/contactserialize.php", false,
                                                $proxyhost, $proxyport, $proxyusername, $proxypassword);

$params = array(''=>'');
$result = $client->call('get_KBase_details', $params, $Server_Path, $Server_Path);

$category_array = $result[0];
$faq_array = $result[2];

if(@array_key_exists('productid',$result[1][0]) && @array_key_exists('productname',$result[1][0]))
	$product_array = $result[1];
elseif(@array_key_exists('id',$result[1][0]) && @array_key_exists('question',$result[1][0]) && @array_key_exists('answer',$result[1][0]))
	$faq_array = $result[1];

$_SESSION['product_array'] = $product_array;
$_SESSION['category_array'] = $category_array;
$_SESSION['faq_array'] = $faq_array;
//echo '<pre>';print_r($result);echo '</pre>';
$search_text = $_REQUEST['search_text'];

$list = '<link href="customerportal.css" rel="stylesheet" type="text/css">';

$list .= '<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td height="150">';

//Knowledge Base Top Band
$list .= '<table width="100%" height="40" border="0" cellspacing="0" cellpadding="0" class="topBand">
			<tr>
			    <td>&nbsp;&nbsp;<img src="images/cp_logo.gif"></td>
		  	</tr>
		</table>';

//Knowledge Base Search
$list .= '<table width="100%" border="0" cellspacing="5" cellpadding="5" class="kbULine">
			<tr>
			    <td class="kbHead">'.$mod_strings['LBL_KNOWLEDGE_BASE'].'</td>
			    <td align="right" width="50%"><a href="cp_index.php"> '.$mod_strings['LNK_CUSTOMER_PORTAL_LOGIN'].'</a></td>
		  	</tr>
			<tr>
				<td><pre>'.$mod_strings['KBASE_DETAILS'].'</pre>
				</td>
			</tr>
			<tr>
				<td>
					<table border="0" cellspacing="0" cellpadding="0">
					<form name="Submit" method="POST" action="index.php">
					<tr>
						<td><strong>'.$mod_strings['LBL_SEARCH'].':</strong>&nbsp;</td>
						<td><input type="text" name="search_text" value="'.$search_text.'" size="30"></td>
						<td>&nbsp;in&nbsp;</td>
						<td>'.getSearchCombo().'</td>
						<td>&nbsp;<input type=submit name=search onclick="this.search.value=true" value='.$mod_strings['LBL_SEARCH'].'>
							<input type=hidden name=fun value="search">
						</td>
					</tr>
					</form>
					</table>
				</td>
			</tr>
		</table>';

$list .= '<div class="kbBand">&nbsp;</div>';

$list .= '</td></tr><tr><td height="100%">';

$list .= '<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">';
$list .= '<tr><td class="kbNav">';

//Categories & Products
$list .= '<table border="0" width="100%" cellspacing="2" cellpadding="0">';
$list .= '<tr><td width="14"><a href="javascript:;toggleView(\'category\')"><img id="categoryimg" src="images/minus.gif" border="0" align="absmiddle"></a></td>';
$list .= '<td width="20"><a href="javascript:;toggleView(\'category\')"><img src="images/category.gif" border="0" align="absmiddle"></a></td>';
$list .= '<td><a href="javascript:;toggleView(\'category\')" class="kbNavHead">'.$mod_strings['LNK_CATEGORY'].'</a></td></tr>';
$list .= '<tr><td></td><td></td><td><div id="category" style="display:block">';
$list .= '<table border="0" width="100%" cellspacing="0" cellpadding="0">';

for($i=0,$j=1;$i<count($category_array);$i++,$j++)
{
	$noof_faqs = getNoofFaqsPerCategory($category_array[$i]);
	$list .= '<tr><td class="kbNavLink"> ';
	$list .= '<a href=index.php?fun=faqs&category_index='.$i.'>'.$category_array[$i].'</a> <span class="kbNavCnt">('.$noof_faqs.')</span></td></tr>';
}
$list .= '</table></div></td></tr></table>';

$list .= '<table border="0" width="100%" cellspacing="2" cellpadding="0">';
$list .= '<tr><td width="14"><a href="javascript:;toggleView(\'products\')"><img id="productsimg" src="images/minus.gif" border="0" align="absmiddle"></a></td>';
$list .= '<td width="20"><a href="javascript:;toggleView(\'products\')"><img src="images/products.gif" border="0" align="absmiddle"></a></td>';
$list .= '<td><a href="javascript:;toggleView(\'products\')" class="kbNavHead">'.$mod_strings['LNK_PRODUCTS'].'</a></td></tr>';
$list .= '<tr><td></td><td></td><td><div id="products" style="display:block">';
$list .= '<table border="0" width="100%" cellspacing="0" cellpadding="0">';

for($i=0,$j=1;$i<count($product_array);$i++,$j++)
{
	$noof_faqs = getNoofFaqsPerProduct($product_array[$i]['productid']);
	$list .= '<tr><td class="kbNavLink"> ';
	$list .= '<a href=index.php?fun=faqs&productid='.$product_array[$i]['productid'].'>';
	$list .= $product_array[$i]['productname'].'</a> <span class="kbNavCnt">('.$noof_faqs.')</span></td></tr>';
}
$list .= '</table></div></td></tr></table>';

$list .= '</td><td class="kbMain">';

$list .= '<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td>';

if($_REQUEST['fun'] == '' || $_REQUEST['fun'] == 'logout')
{
	$faq_array = $_SESSION['faq_array'];
	for($i=0;$i<count($faq_array);$i++)
	{
		$temp[$i] .= $faq_array[$i]['faqmodifiedtime'];
	}
	$list .= getLatestlyCreatedFaqList();
}

if($_REQUEST['fun'] == 'search')
{
	$search_text = $_REQUEST['search_text'];
	$search_category = explode(":",$_REQUEST['search_category']);
	$searchlist .= getSearchResult($search_text,$search_category[1],$search_category[0]);
	$list .= $searchlist;	
}
if($_REQUEST['fun'] == 'faqs')
{
	if($_REQUEST['category_index'] != '')
		$faqlist .= ListFaqsPerCategory($_REQUEST['category_index']);
	elseif($_REQUEST['productid'] != '')
		$faqlist .= ListFaqsPerProduct($_REQUEST['productid']);
	$list .= $faqlist;
}
if($_REQUEST['fun'] == 'faq_comments')
{
	$commentslist .= Faq_Comments($_REQUEST['faqid']);
	$list .= $commentslist;
}
if($_REQUEST['fun'] == 'save')
{
	$faqid = $_REQUEST['faqid'];
	$comment = $_REQUEST['comments'];
	$params = array('faqid'=>"$faqid",'comments'=>"$comment");
	$result = $client->call('save_faq_comment', $params, $Server_Path, $Server_Path);

	$_SESSION['product_array'] = $result[0];
	$_SESSION['category_array'] = $result[1];
	$_SESSION['faq_array'] = $result[2];

        $commentslist .= Faq_Comments($faqid);
        $list .= $commentslist;
}

$list .= '</td></tr></table>';
$list .= '</td></tr></table>';

echo $list;

?>
<script language="JavaScript" src="js/cookies.js"></script>
<script>
function toggleView(view) {
	if (document.getElementById(view).style.display=="block") {
		document.getElementById(view).style.display="none"
		document.getElementById(view+"img").src="images/plus.gif"
		set_cookie("kb_"+view,"none")
	} else {
		document.getElementById(view).style.display="block"
		document.getElementById(view+"img").src="images/minus.gif"
		set_cookie("kb_"+view,"block")
	}
}

var view=new Array("category","products")
for (i=0;i<view.length;i++) {
	if (get_cookie("kb_"+view[i])==null || get_cookie("kb_"+view[i])=="" || get_cookie("kb_"+view[i])=="block") {
		document.getElementById(view[i]).style.display="block"
		document.getElementById(view[i]+"img").src="images/minus.gif"
	} else {
		document.getElementById(view[i]).style.display="none"
		document.getElementById(view[i]+"img").src="images/plus.gif"
	}
}
</script>
