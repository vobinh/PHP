// JavaScript Document
   var myInterval;
   var count = 0;
   var startTime;

   function startStopCountUp()
   {
      
         startTime = new Date();
         myInterval = window.setInterval('StartCountUp()',333);
   }
  
   function StartCountUp()
   {
      var myDate = new Date();
      count = myDate - startTime + count;
      startTime = myDate;
  
      spanTimer.innerHTML = showDiffTime(count);
	  $("#hd_timeduration").val(showDiffTime(count));
   }
 
   function StopCountUp()
   {
      window.clearInterval(myInterval);
   }
 
   function ResetCountUp()
   {
      StopCountUp();
      document.getElementById("btnCountUp").value = "Start";  
      document.getElementById("btnReset").style.display = 'none';
      count = 0;
      spanTimer.innerHTML = '0';
   }

   function showDiffTime(diffTime)
   {
      var miliseconds = diffTime % 1000;
      var scratch = Math.floor((diffTime - miliseconds) / 1000);
      var seconds = scratch % 60;
      scratch = Math.floor((scratch - seconds) / 60);
      var minutes = scratch % 60;
      scratch = Math.floor((scratch - minutes) / 60);
      var hours = scratch % 24;
      scratch = Math.floor((scratch - hours) / 24);
      var days = scratch;
  
      var displayDiffTime = ""
  
      if (days > 0)
         displayDiffTime += days + ' days, ';
   
      if (hours > 9)
         displayDiffTime += hours + ':';
      else
         displayDiffTime += '0' + hours + ':';
   
      if (minutes > 9)
         displayDiffTime += minutes + ':';
      else
         displayDiffTime += '0' + minutes + ':'; 
  
      if (seconds > 9)
         displayDiffTime += seconds ;
      else
         displayDiffTime += '0' + seconds ; 
   
    /*  if (miliseconds > 99)
         displayDiffTime += miliseconds;
      else if (miliseconds > 9)
         displayDiffTime += '0' + miliseconds;
      else
         displayDiffTime += '00' + miliseconds;   */
      return displayDiffTime;
   }
   
  
