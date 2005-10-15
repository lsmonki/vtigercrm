<?php
/*
CREATE TABLE `layout` (
  `set` varchar(50) NOT NULL default '',
  `item` varchar(50) NOT NULL default '',
  `order` int(9) NOT NULL default '0',
  PRIMARY KEY  (`set`,`item`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `layout`
--

INSERT INTO `layout` VALUES ('right_col', 'Seven', 0);
INSERT INTO `layout` VALUES ('right_col', 'Six', 0);
INSERT INTO `layout` VALUES ('center', 'Four', 0);
INSERT INTO `layout` VALUES ('center', 'Two', 0);
INSERT INTO `layout` VALUES ('center', 'Three', 0);
INSERT INTO `layout` VALUES ('left_col', 'Five', 0);
INSERT INTO `layout` VALUES ('left_col', 'One', 0);
INSERT INTO `layout` VALUES ('sajax1', 'Item 1', 0);
INSERT INTO `layout` VALUES ('sajax2', 'Item 2', 0);
INSERT INTO `layout` VALUES ('sajax2', 'Item 3', 0);
*/
//File contributed by Mike Crowe for Ordering Fields

mysql_connect('localhost', 'username', 'password');
mysql_select_db('database');
function parse_data($data)
{
  $containers = explode(":", $data);
  foreach($containers AS $container)
  {
      $container = str_replace(")", "", $container);
      $i = 0;
      $lastly = explode("(", $container);
      $values = explode(",", $lastly[1]);
      foreach($values AS $value)
      {
        if($value == '')
        {
            continue;
        }
        $final[$lastly[0]][] = $value;
        $i ++;
      }
  }
    return $final;
}

function update_db($data_array, $col_check)
{

  foreach($data_array AS $set => $items)
  {
     $i = 0;
     foreach($items AS $item)
     {
       $item = mysql_escape_string($item);
       $set  = mysql_escape_string($set);
       
       mysql_query("UPDATE layout SET `set` = '$set', `order` = '$i'  WHERE `item` = '$item' $col_check");
       $i ++;
     }
  }
}

// Lets setup Sajax
require_once('Sajax.php');
sajax_init();
// $sajax_debug_mode = 1;

function sajax_update($data)
{
  $data = parse_data($data);
  update_db($data, "AND (`set` = 'sajax1' OR `set` = 'sajax2')");
  return 'y';
}

sajax_export("sajax_update");
sajax_handle_client_request();


if(isset($_POST['order']))
{
  $data = parse_data($_POST['order']);
  update_db($data, "AND (`set` = 'left_col' OR `set` = 'right_col' OR `set` = 'center')");
  // redirect so refresh doesnt reset order to last save
  header("location: dd.php");
  exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN">
<html><head><title>Drag Drop</title>


<style type="text/css">
#left_col {
    width: 180px;
    float: left;
    margin-left: 5px;
}

#center {
    width: 180px;
    float: left;
    margin-left: 5px;
}

#right_col {
    width: 180px;
    float: left;
    margin-left: 5px;
}

#sajax1 {
    width: 180px;
    float: left;
    margin-left: 5px;
}

#sajax2 {
    width: 180px;
    float: left;
    margin-left: 5px;
}

form {
  clear: left;
}

body {
	background: #FCFEF4  repeat-x;
	margin: 10px 10px 10px 10px;
	font-family: Arial, Verdana, Helvetica;
	font-size: 76%;
	color: #3F3F3F;
	text-align: left;
	}

h2 {
	color: #7DA721;
	font-weight: normal;
	font-size: 14px;
	margin: 20px 0 0 0;
	}

br {
        clear: left;
}
</style>

<link rel="stylesheet" href="dd_files/lists.css" type="text/css">
<script language="JavaScript" type="text/javascript" src="dd_files/coordinates.js"></script>
<script language="JavaScript" type="text/javascript" src="dd_files/drag.js"></script>
<script language="JavaScript" type="text/javascript" src="dd_files/dragdrop.js"></script>
<script language="JavaScript" type="text/javascript"><!--
<?php
sajax_show_javascript();
?>
       function confirm(z)
       {
          window.status = 'Sajax version updated';
       }

        function onDrop() {
          var data = DragDrop.serData('g2'); 
          x_sajax_update(data, confirm);
       }

	window.onload = function() {
        
		var list = document.getElementById("left_col");
		DragDrop.makeListContainer( list, 'g1' );
		list.onDragOver = function() { this.style["background"] = "#EEF"; };
		list.onDragOut = function() {this.style["background"] = "none"; };

		list = document.getElementById("center");
		DragDrop.makeListContainer( list, 'g1' );
                list.onDragOver = function() { this.style["background"] = "#EEF"; };
		list.onDragOut = function() {this.style["background"] = "none"; };
                
                list = document.getElementById("right_col");
		DragDrop.makeListContainer( list, 'g1' );
                list.onDragOver = function() { this.style["background"] = "#EEF"; };
		list.onDragOut = function() {this.style["background"] = "none"; };

                list = document.getElementById("sajax1");
                DragDrop.makeListContainer( list, 'g2' );
                list.onDragOver = function() { this.style["background"] = "#EEF"; };
                list.onDragOut = function() {this.style["background"] = "none"; };
                list.onDragDrop = function() {onDrop(); };
                
                list = document.getElementById("sajax2");
                DragDrop.makeListContainer( list, 'g2' );
                list.onDragOver = function() { this.style["background"] = "#EEF"; };
                list.onDragOut = function() {this.style["background"] = "none"; };
                list.onDragDrop = function() {onDrop(); };
	};
        
        function getSort()
        {
          order = document.getElementById("order");
          order.value = DragDrop.serData('g1', null);
        }
        
        function showValue()
        {
          order = document.getElementById("order");
          alert(order.value);
        }
	//-->
</script></head>

<body>

<h2>Form update</h2>
<p>This shows the order being updated in the DB when the submit button is clicked.</p>
<br />
<ul id="left_col" class="sortable boxy">
  <?php
$r = mysql_query("SELECT * FROM layout WHERE `set` = 'left_col' ORDER BY `order` ASC");
while($rw = mysql_fetch_array($r))
{
  echo '<li id="'.$rw['item'].'">'.$rw['item'].'</li>';
}
?>
</ul>


<ul id="center" class="sortable boxy">
   <?php
$r = mysql_query("SELECT * FROM layout WHERE `set` = 'center' ORDER BY `order` ASC");
while($rw = mysql_fetch_array($r))
{
  echo '<li id="'.$rw['item'].'">'.$rw['item'].'</li>';
}
?>

</ul>


<ul id="right_col" class="sortable boxy">
   <?php
$r = mysql_query("SELECT * FROM layout WHERE `set` = 'right_col' ORDER BY `order` ASC");
while($rw = mysql_fetch_array($r))
{
  echo '<li id="'.$rw['item'].'">'.$rw['item'].'</li>';
}
?>
</ul>

<form action="" method="post">
  <br />
  <input type="hidden" name="order" id="order" value="" />
  <input type="submit" onclick="getSort()" value="Update Order" />
</form>



<h2>(S)Ajax Example</h2>
<p>In this example the fields are updated automatically in the DB when the item is dropped, using the AJAX method (SAJAX to implement with PHP)</p>
<br />

<ul id="sajax1" class="sortable boxy">
  <?php
$r = mysql_query("SELECT * FROM layout WHERE `set` = 'sajax1' ORDER BY `order` ASC");
while($rw = mysql_fetch_array($r))
{
  echo '<li id="'.$rw['item'].'">'.$rw['item'].'</li>';
}
?>
</ul>

<ul id="sajax2" class="sortable boxy">
  <?php
$r = mysql_query("SELECT * FROM layout WHERE `set` = 'sajax2' ORDER BY `order` ASC");
while($rw = mysql_fetch_array($r))
{
  echo '<li id="'.$rw['item'].'">'.$rw['item'].'</li>';
}
?>

</ul>

<br />
<br />
<p>View the <a href="dd.phps">source php</a> code (code for <a href="Sajax.phps">Sajax.php</a>).<br />
<a href="dd.zip">Download Files</a> | <a href="http://www.cyberdummy.co.uk/2005/07/13/multi-list-drag-and-drop/">Comments</a></p>

<h2>Update (18 July 05)</h2>
<p>Simplified code (no more deleting items) made little more secure.</p>

<h2>Update (15 July 05)</h2>
<p>Added a grouping feature so you can have groups of lists to drag between and not let them get mixed.
<br />Added a Sjax example implementation.
</p>

<h2>Update (14 July 05)</h2>
<p>Now no need for a trash container implemented a bounce back feature.</p>



</body></html>
