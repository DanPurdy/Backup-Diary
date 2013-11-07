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

      getSec();
      getHour();
      getMin();
 
      
      
      
 
});