function countdown( elementName, minutes, seconds )
{
    var  endTime, hours, mins, msLeft, time;

    function twoDigits( n )
    {
        return (n <= 9 ? "0" + n : n);
    }

    function updateTimer()
    {
        msLeft = endTime - (+new Date);
        if ( msLeft < 1000 ) {
           $("span#"+elementName).html("countdown's over!");
		   $("#hd_timeduration").val('00:00:00');
		   document.forms['frm'].submit();
        } else {
            time = new Date( msLeft );
            hours = time.getUTCHours();
			if(hours==0)hours='00';
			
            mins = time.getUTCMinutes();
			if(mins==0)mins='0';
            $("span#"+elementName).html((hours ? hours + ':' + twoDigits( mins ) : mins) + ':' + twoDigits( time.getUTCSeconds() )) ;
            setTimeout( updateTimer, time.getUTCMilliseconds() + 500 );
			$("#hd_timeduration").val((hours ? hours + ':' + twoDigits( mins ) : mins) + ':' + twoDigits( time.getUTCSeconds() ));
        }
    }

    endTime = (+new Date) + 1000 * (60*minutes + seconds) + 500;
    updateTimer();
}