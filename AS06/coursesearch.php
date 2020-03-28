<?php

printForm(); 

#-----------------------------------------------------------------------------
// display the entry form for course search
function printForm(){
	
	echo '<h2>Course Lookup</h2>';
	
	// print user entry form
	echo "<form action='courses.php'>";
	echo "Course Prefix (Department)<br/>";
	echo "<input type='text' placeholder='CS' name='prefix'><br/>";
	echo "Course Number<br/>";
	echo "<input type='text' placeholder='116' name='courseNumber'><br/>";
	echo "Instructor<br/>";
	echo "<input type='text' placeholder='gpcorser' name='instructor'><br/>";
	echo "Meeting Days<br/>";
	echo "<select name='courseDay' type='text'>";
	echo "<option value='-1'>All</option>";
	echo "<option value='0'>M</option>";
	echo "<option value='1'>T</option>";
	echo "<option value='2'>W</option>";
	echo "<option value='3'>MW</option>";
	echo "<option value='4'>R</option>";
	echo "<option value='5'>TR</option>";
	echo "<option value='6'>F</option>";
	echo "</select><br/>";
	//echo "Building/Room<br/>";
	//echo "<input type='text' name='building'>";
	//echo "<input type='text' name='room'><br/>";
	echo "<input type='submit' value='Submit'>";
	echo "</form>";
}
