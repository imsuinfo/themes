// Array function
function makeArray(len) {
  for (var i = 0; i < len; i++) this[i] = null;
  this.length = len;

  // Array of day names
  var dayNames = new makeArray(7);
  dayNames[0] = "Sunday";
  dayNames[1] = "Monday";
  dayNames[2] = "Tuesday";
  dayNames[3] = "Wednesday";
  dayNames[4] = "Thursday";
  dayNames[5] = "Friday";
  dayNames[6] = "Saturday";

  // Array of month Names
  var monthNames = new makeArray(12);
  monthNames[0] = "January";
  monthNames[1] = "February";
  monthNames[2] = "March";
  monthNames[3] = "April";
  monthNames[4] = "May";
  monthNames[5] = "June";
  monthNames[6] = "July";
  monthNames[7] = "August";
  monthNames[8] = "September";
  monthNames[9] = "October";
  monthNames[10] = "November";
  monthNames[11] = "December";

  var now = new Date();
  var day = now.getDay();
  var month = now.getMonth();
  var year = now.getYear();
  var date = now.getDate();
  if (year < 2000)
  year = year + 1900;
  var syear = ""
  syear = year + " "
  syear = syear.substring(2,4)
}

function doCheck()
{
  //alert(document.theform.dorm.value);
  if (document.theform.dorm.value == "none")
  {
    alert("You must select a dorm!");
  }
  else
  {
    document.theform.submit();
  }
}
