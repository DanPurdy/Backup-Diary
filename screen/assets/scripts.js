$(function() {

      getSec = function(){
            var seconds = new Date().getSeconds();
            var sdegree = seconds * 6;
            var srotate = "rotate(" + sdegree + "deg)";
      
            $("#sec").css({ "transform": srotate });
            setTimeout(getSec, 1000);
      };

      getMin = function(){
            var mins = new Date().getMinutes();
            var mdegree = mins * 6;
            var mrotate = "rotate(" + mdegree + "deg)";
            
            $("#min").css({ "transform" : mrotate });

            setTimeout(getMin, 1000);
      };

      getHour = function(){
            var hours = new Date().getHours();
            var mins = new Date().getMinutes();
            var hdegree = hours * 30 + (mins / 2);
            var hrotate = "rotate(" + hdegree + "deg)";
      
            $("#hour").css({ "transform": hrotate});

            setTimeout(getHour, 1000);
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
                  }
            });
            setTimeout(getScreen, 15*60*1000);
      };
      
      getSec();
      getHour();
      getMin();
      getScreen();
      
      
      
 
});