<?php
/**
 * demo_postback_nohtml.php is a single page web application that allows us to request and view 
 * a customer's name
 *
 * This version uses no HTML directly so we can code collapse more efficiently
 *
 * This page is a model on which to demonstrate fundamentals of single page, postback 
 * web applications.
 *
 * Any number of additional steps or processes can be added by adding keywords to the switch 
 * statement and identifying a hidden form field in the previous step's form:
 *
 *<code>
 * <input type="hidden" name="act" value="next" />
 *</code>
 * 
 * The above live of code shows the parameter "act" being loaded with the value "next" which would be the 
 * unique identifier for the next step of a multi-step process
 *
 * @package ITC281
 * @author Solomon Mayfield<smayfio1@gmail.com>
 * @version 1.1 2011/10/11
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @todo finish instruction sheet
 * @todo add more complicated checkbox & radio button examples
 */

# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
 
/*
$config->metaDescription = 'Web Database ITC281 class website.'; #Fills <meta> tags.
$config->metaKeywords = 'SCCC,Seattle Central,ITC281,database,mysql,php';
$config->metaRobots = 'no index, no follow';
$config->loadhead = ''; #load page specific JS
$config->banner = ''; #goes inside header
$config->copyright = ''; #goes inside footer
$config->sidebar1 = ''; #goes inside left side of page
$config->sidebar2 = ''; #goes inside right side of page
$config->nav1["page.php"] = "New Page!"; #add a new page to end of nav1 (viewable this page only)!!
$config->nav1 = array("page.php"=>"New Page!") + $config->nav1; #add a new page to beginning of nav1 (viewable this page only)!!
*/

//END CONFIG AREA ----------------------------------------------------------

# Read the value of 'action' whether it is passed via $_POST or $_GET with $_REQUEST
if(isset($_REQUEST['act'])){$myAction = (trim($_REQUEST['act']));}else{$myAction = "";}

switch ($myAction) 
{//check 'act' for type of process
	case "display": # 2)Display user's name!
	 	showBallplayers();
	 	break;
	case "destroy": # 2)destroy user's name!
	 	destroyBallplayers();
	 	break;
	default: # 1)Ask user to enter their ball player data 
	 	showForm();
}
function destroyBallplayers()
{
	unset($_SESSION['myBallPlayers']);
	feedback("ballplayers cleared"); #will feedback to submitting page via session variable
	myRedirect(THIS_PAGE);

}
function showForm()
{# shows form so user can enter their name.  Initial scenario
	get_header(); #defaults to header_inc.php	
	
	echo 
	'<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
		function checkForm(thisForm)
		{//check form data for valid info
			if(empty(thisForm.name,"Please Enter Your Players Name")){return false;}
			if(empty(thisForm.team,"Please Enter Your Players team")){return false;}
			if(empty(thisForm.homer,"Please Enter Your Players homer")){return false;}
			return true;//if all is passed, submit!
		}
	</script>
	<h3 align="center">' . smartTitle() . '</h3>
	<p align="center">Please enter your name</p> 
	<form action="' . THIS_PAGE . '" method="post" onsubmit="return checkForm(this);">
		<table align="center">
			<tr>
				<td align="right">
					Name
				</td>
				<td>
					<input type="text" name="name" /><font color="red"><b>*</b></font> <em>(alphabetic only)</em>
				</td>
			</tr>
			<tr>
				<td align="right">
					team
				</td>
				<td>
					<input type="text" name="team" /><font color="red"><b>*</b></font> <em>(alphabetic only)</em>
				</td>
			</tr>
			<tr>
				<td align="right">
					homer
				</td>
				<td>
					<input type="text" name="homer" /><font color="red"><b>*</b></font> <em>(alphabetic only)</em>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="2">
					<input type="submit" value="Please Enter Your Name"><em>(<font color="red"><b>*</b> required field</font>)</em>
				</td>
			</tr>
		</table>
		<input type="hidden" name="act" value="display" />
	</form>
	';
	get_footer(); #defaults to footer_inc.php
}

function showBallplayers()
{#form submits here we show entered name
	get_header(); #defaults to footer_inc.php
	
	echo '<pre>';
	var_dump($_POST);
	echo '</pre>';
	die;
	/*
	dumpDie($_POST);
	*/
	if(!isset($_POST['name']) || $_POST['name'] == '')
	{//data must be sent	
		feedback("No form data submitted"); #will feedback to submitting page via session variable
		myRedirect(THIS_PAGE);
	}  
	
	if(!isset($_POST['team']) || $_POST['team'] == '')
	{//data must be sent	
		feedback("No form data submitted"); #will feedback to submitting page via session variable
		myRedirect(THIS_PAGE);
	}  
	
	if(!isset($_POST['homer']) || $_POST['homer'] == '')
	{//data must be sent	
		feedback("No form data submitted"); #will feedback to submitting page via session variable
		myRedirect(THIS_PAGE);
	}  
	
	
	
	
	if(!ctype_alnum($_POST['name']))
	{//data must be alphanumeric only	
		feedback("Only letters and numbers are allowed.  Please re-enter your name."); #will feedback to submitting page via session variable
		myRedirect(THIS_PAGE);
	}
	
	$name = strip_tags($_POST['name']);# here's where we can strip out unwanted data
	$team = strip_tags($_POST['team']);# here's where we can strip out unwanted data
	$homer = (int)$_POST['homer'];# here's where we can strip out unwanted data
	$myBallPlayers[] = new BallPlayer($name,$team,$homer);
	//dumpDie($myBallPlayer);
	echo '<h3 align="center">' . smartTitle() . '</h3>';
	if(!isset($_SESSION)){session_start();}/*if session exists add to it. if not start new*/
	if(isset($_SESSION['myBallPlayers']))
	{//add to existing session variable
		$myBallPlayers = $_SESSION['myBallPlayers'];
	}else{//start new session
		$myBallPlayers = array();
	}
	
	$myBallPlayers[] = new BallPlayer($name,$team,$homer);
	
	$_SESSION['myBallPlayers'] = $myBallPlayers; 
	
	foreach($myBallPlayers as $player)
	{
		echo "Name: " . $player->name . "<br />";
		echo "Team: " . $player->name . "<br />";
		echo "Homer: " . $player->homer . "<br />";
		$TotalHomers += $player->homer;
	}
	echo "they hit " . $TotalHomers . "total homers!";
	echo "<br />they hit " . $TotalHomers/count($myBallPlayers) . "average homers!";
	
	<a href = ''>
	dumpDie($_SESSION['myBallPlayers']);
	
	//unset($_SESSION['myBallPlayers']);
	echo '<p align="center"><a href="' . THIS_PAGE . '">RESET</a></p>';
	echo '<a href="' . THIS_PAGE . '?act=destroy">DESTROY</a></';
	get_footer(); #defaults to footer_inc.php
}

	class BallPlayer
{
	//public member variables (properties)
 	public $name = '';
 	public $team = '';
 	public $homers = 0;
	
    function __construct($name,$team,$homers)
	{//constructor sets stage by adding data to an instance of the object
		
		$this->name = $name;
		$this->team = $team;
		$this->homers = $homers;
	}
}



