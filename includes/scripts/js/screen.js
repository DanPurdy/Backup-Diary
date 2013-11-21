$(function() {

      var tags ={
            days : ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
            months : ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
      };

      setClock = function(){
            var   date        = new Date(),
                  seconds     = date.getSeconds(),
                  mins        = date.getMinutes(),
                  hours       = date.getHours,
                  sdegree     = seconds * 6,
                  mdegree     = mins *6,
                  hdegree     = hours * 30 + (mins/2),
                  srotate     = "rotate(" + sdegree + "deg)",
                  mrotate     = "rotate(" + mdegree + "deg)",
                  hrotate     = "rotate(" + hdegree + "deg)";
      
            $("#sec").css({ "transform": srotate });
            $("#min").css({ "transform" : mrotate });
            $("#hour").css({ "transform": hrotate});
            
            setTimeout(setClock, 1000);
      };

      screenDate = function(){
            var   date        = new Date(),
                  weekDay     = date.getDay(),
                  day         = date.getDate(),
                  month       = date.getMonth(),
                  year        = date.getFullYear(),
                  daySep      = "th";

            if(day === 1 || day === 21 || day === 31){
                  daySep="st";
            }else if(day === 2 || day === 22){
                  daySep="nd";
            }else if(day === 3 || day === 23){
                  daySep = "rd";
            }

            $('.day').text(tags.days[weekDay]);
            $('.date').text(day + daySep+" " +tags.months[month].substring(0,3)+" "+year);

      };
      

      

      getScreen = function(){
            var   url="get_screen.php",
            val = 1;

            $.ajax({                            //Start and Ajax call
                  type:"POST",
                  url: url,
                  data: { term: val },            //send the studio value as a parameter to the php page, can be accessed with $_REQUEST or $_GET
                  datatype: "json",

                  success: function(data){
                        $('.sess-details').remove(); //remove previous details
                        $('.wrapper').append(data); //append the new details

                        screenDate();
                  }
            });
            setTimeout(getScreen, 15*60*1000);
      };
      
      setClock();
      getScreen();
      
});