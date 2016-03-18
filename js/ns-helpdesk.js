function doCheck()
{
  //alert(document.theform.dept.value);

  if (document.theform.dept.value == "none")
  {
    alert("You must select a department!");
  }
  else
  {
    document.theform.submit();
  }
}
