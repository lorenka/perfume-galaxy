<?php
$perfume = $_GET['perfume'];
$perfume_image = "./images/perfumes/" . $perfume . ".png";
$perfume_name = $_GET['name'];
?>

<html>
 <head>
	<link rel="stylesheet" type="text/css" href="http://lorrainesawicki.com/projects/perfume-galaxy/perfume-galaxy.css">
 </head>

<header>
      <h1><a href="http://lorrainesawicki.com/projects/perfume-galaxy/index.html">Perfume Galaxy</a></h1>
      <h2><a href="http://lorrainesawicki.com/projects/perfume-galaxy/index.html">< back to center of galaxy</a></h2>
   <div id="mini-info-card">
      <h2 style="width:200px;" id="perfume-name"></h2>
   </div>
</header>


 <style type="text/css">
    .perfumes {
       clear:both;
    }

    #mainBubble {
      box-shadow: 0 0 4px rgba(0,0,0,0);
      height: 500px;
      position: relative;
      width: 75%;
      float:left;
    }
             
    #mainBubble svg {
      left: 0;
      position: absolute;
      top: 0;
    }
                         
    #mainBubble circle.topBubble {
//      fill: #aaa;
      stroke: #666;
      stroke-width: 1.5px;
     }

    #perfume-image {
      float:left;
      margin:150px 0px 0px 50px;
      display:inline-block;
    } 

    img {
      width:100px;
      height:100px;
      border:none;
    }
    </style>
    <script type="text/javascript" src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <title>
      Perfume Galaxy Note Detail Menu
    </title>

<?php include 'header.php;'?>
   
  <div id="perfume-image">
    <img src="<?php echo $perfume_image; ?>">
    <h2 style="max-width:200px;font-size:22px;font-weight:strong;"><?php echo $perfume_name; ?></h2>
    <div style="max-width:200px;" class="perfumes">
    </div>
  </div>
  
  <div id="mainBubble" style="height: 500px;"><svg class="mainBubbleSVG" width="1000" height="652"><text id="bubbleItemNote" x="10" y="450.12" font-size="12" dominant-baseline="middle" alignment-baseline="middle" style="fill: rgb(136, 136, 136);">D3.js bubble menu developed by Shipeng Sun (sunsp.gis@gmail.com), Institute of Environment, University of Minnesota, and University of Springfield, Illinois.</text></svg></div>

  <script>
   var w = window.innerWidth*0.68*0.95;
   var h = Math.ceil(w*0.7);
   var oR = 0;
   var nTop = 0;
    
   var svgContainer = d3.select("#mainBubble")
      .style("height", h+"px");
    
   var svg = d3.select("#mainBubble").append("svg")
        .attr("class", "mainBubbleSVG")
        .attr("width", w)
        .attr("height",h)
        .on("mouseleave", function() {return resetBubbles();});
         
   var mainNote = svg.append("text")
    .attr("id", "bubbleItemNote")
    .attr("x", 10)
    .attr("y", w/2-15)
    .attr("font-size", 12)
    .attr("dominant-baseline", "middle")
    .attr("alignment-baseline", "middle")
    .style("fill", "#888888")
    //.text(function(d) {return "D3.js bubble menu developed by Shipeng Sun (sunsp.gis@gmail.com), Institute of Environment, University of Minnesota, and University of Springfield, Illinois.";});   
 
    d3.json("./bubble-menu-perfumes/<?php echo $perfume;?>.json", function(error, root) {
        console.log(error);
     
        var bubbleObj = svg.selectAll(".topBubble")
                .data(root.children)
            .enter().append("g")
                .attr("id", function(d,i) {return "topBubbleAndText_" + i});
             
        console.log(root);  
        nTop = root.children.length;
        oR = w/(1+3*nTop);  
 
    h = Math.ceil(w/nTop*2);
    svgContainer.style("height",h+"px");
         
        var colVals = d3.scale.category10();
         
bubbleObj.append("svg:circle")
     .attr("r", function(d) { return Math.sqrt(d.size) / 10 || 4.5; })
      .style("fill", "#eee");

//this is where the "large" bubble is...
        bubbleObj.append("circle")
            .attr("class", "topBubble")
            .attr("id", function(d,i) {return "topBubble" + i;})
            .attr("r", function(d) { return oR; })
            .attr("cx", function(d, i) {return oR*(3*(1+i)-1);})
            .attr("cy", (h+oR)/3)
//            .style("fill", function(d,i) { return colVals(i); }) // #1f77b4
            .style("opacity",0)
            .on("mouseover", function(d,i) {return activateBubble(d,i);});
         
             
        bubbleObj.append("text")
            .attr("class", "topBubbleText")
            .attr("x", function(d, i) {return oR*(3*(1+i)-1);})
            .attr("y", (h+oR)/3)
//        .style("fill", function(d,i) { return colVals(i); }) // #1f77b4
            .style("fill", "#FFFFFF") // #1f77b4
            .attr("font-size", 30)
            .attr("text-anchor", "middle")
            .attr("dominant-baseline", "middle")
            .attr("alignment-baseline", "middle")
            .text(function(d) {return d.name })      
            .on("mouseover", function(d,i) {return activateBubble(d,i);});
//large bubble ends here
         
         
        for(var iB = 0; iB < nTop; iB++)
        {
            var childBubbles = svg.selectAll(".childBubble" + iB)
                .data(root.children[iB].children)
                .enter().append("g");
                 
        //var nSubBubble = Math.floor(root.children[iB].children.length/2.0);   
             
            childBubbles.append("circle")
                .attr("class", "childBubble" + iB)
                .attr("id", function(d,i) {return "childBubble_" + iB + "sub_" + i;})
                .attr("r",  function(d) {return oR/3.0;})
                .attr("cx", function(d,i) {return (oR*(3*(iB+1)-1) + oR*1.5*Math.cos((i-1)*45/180*3.1415926));})
                .attr("cy", function(d,i) {return ((h+oR)/3 +        oR*1.5*Math.sin((i-1)*45/180*3.1415926));})
                .attr("cursor","pointer")
                .style("stroke", "white")
                .style("stroke-width", 1.5)
                .style("opacity",0.5)
            //    .style("fill", "#eee")
                .on("click", function(d,i) {
//                window.open(d.address);                 
// next iteration: .... note to perfume just updates underneath the bubbles of notes.
		window.open('./note-to-perfume.php?note=' + d.note );
              })
            .on("mouseover", function(d,i) {
              var noteText = "";
              if (d.note == null || d.note == "") {
                noteText = d.address;
              } else {
                noteText = d.note;
              }
              d3.select("#bubbleItemNote").text(noteText);
              })
            .append("svg:title")
            .text(function(d) { return d.address; });   
 
            childBubbles.append("text")
                .attr("class", "childBubbleText" + iB)
                .attr("x", function(d,i) {return (oR*(3*(iB+1)-1) + oR*1.5*Math.cos((i-1)*45/180*3.1415926));})
                .attr("y", function(d,i) {return ((h+oR)/3 +        oR*1.5*Math.sin((i-1)*45/180*3.1415926));})
                .style("opacity",0.5)
                .attr("text-anchor", "middle")
//            .style("fill", function(d,i) { return colVals(iB); }) // #1f77b4
                .style("fill", "#FFFFFF")
                .attr("font-size", 16)
                .attr("cursor","pointer")
                .attr("dominant-baseline", "middle")
            .attr("alignment-baseline", "middle")
                .text(function(d) {return d.name})      
                .on("click", function(d,i) {
                //window.open(d.address);
		//window.open('./note-to-perfume.php?note=' + d.name);
                    noteToPerfumeMatch(d.name);
                }); 
 
        }

         
        }); 
 
    resetBubbles = function () {
      w = window.innerWidth*0.68*0.95;
      oR = w/(1+3*nTop);
       
      h = Math.ceil(w/nTop*2);
      svgContainer.style("height",h+"px");
 
      mainNote.attr("y",h-15);
           
      svg.attr("width", w);
      svg.attr("height",h);       
       
      var t = svg.transition()
          .duration(650);
         
        t.selectAll(".topBubble")
            .attr("r", function(d) { return oR; })
            .attr("cx", function(d, i) {return oR*(3*(1+i)-1);})
            .attr("cy", (h+oR)/3);
 
        t.selectAll(".topBubbleText")
        .attr("font-size", 24)
            .attr("x", function(d, i) {return oR*(3*(1+i)-1);})
            .attr("y", (h+oR)/3);
     
      for(var k = 0; k < nTop; k++) 
      {
        t.selectAll(".childBubbleText" + k)
                .attr("x", function(d,i) {return (oR*(3*(k+1)-1) + oR*1.5*Math.cos((i-1)*45/180*3.1415926));})
                .attr("y", function(d,i) {return ((h+oR)/3 +        oR*1.5*Math.sin((i-1)*45/180*3.1415926));})
            .attr("font-size", 12)
                .style("opacity",0.5);
 
        t.selectAll(".childBubble" + k)
                .attr("r",  function(d) {return oR/3.0;})
            .style("opacity",0.5)
                .attr("cx", function(d,i) {return (oR*(3*(k+1)-1) + oR*1.5*Math.cos((i-1)*45/180*3.1415926));})
                .attr("cy", function(d,i) {return ((h+oR)/3 +        oR*1.5*Math.sin((i-1)*45/180*3.1415926));});
                     
      }   
    }

    noteToPerfumeMatch = function(perfumeNote) {

        d3.json("./note-to-perfume.json", function(error, json) {
     
        if (error) return console.warn(error);
        data = json;
        perfumeList = "<p style=\"color:#FFFFFF;margin-top:90px;\">Perfumes containing " + perfumeNote + " note:</p><br/>";
        
        for (var notes in data) {
          note = data[notes]['note'];
          perfumeArray = data[notes]['perfumes'];
  
          if (note == perfumeNote) {
              perfumeArray.forEach(function(item,index,array) {
                    perfumeList += "<p style=\"margin:0px;color:#FFFFFF\">" + item + "</p><br/>";
              });
          }

        }

            document.querySelector('.perfumes').innerHTML = perfumeList;
         
        }); 

    } 

         
         
        function activateBubble(d,i) {
            // increase this bubble and decrease others
            var t = svg.transition()
                .duration(d3.event.altKey ? 7500 : 350);
     
            t.selectAll(".topBubble")
                .attr("cx", function(d,ii){
                    if(i == ii) {
                        // Nothing to change
                        return oR*(3*(1+ii)-1) - 0.6*oR*(ii-1);
                    } else {
                        // Push away a little bit
                        if(ii < i){
                            // left side
                            return oR*0.6*(3*(1+ii)-1);
                        } else {
                            // right side
                            return oR*(nTop*3+1) - oR*0.6*(3*(nTop-ii)-1);
                        }
                    }               
                })
                .attr("r", function(d, ii) { 
                    if(i == ii)
                        return oR*1.8;
                    else
                        return oR*0.8;
                    });
                     
            t.selectAll(".topBubbleText")
                .attr("x", function(d,ii){
                    if(i == ii) {
                        // Nothing to change
                        return oR*(3*(1+ii)-1) - 0.6*oR*(ii-1);
                    } else {
                        // Push away a little bit
                        if(ii < i){
                            // left side
                            return oR*0.6*(3*(1+ii)-1);
                        } else {
                            // right side
                            return oR*(nTop*3+1) - oR*0.6*(3*(nTop-ii)-1);
                        }
                    }               
                })          
                .attr("font-size", function(d,ii){
                    if(i == ii)
                        return 30*1.5;
                    else
                        return 30*0.6;              
                });
     
            var signSide = -1;
            for(var k = 0; k < nTop; k++) 
            {
                signSide = 1;
                if(k < nTop/2) signSide = 1;
                t.selectAll(".childBubbleText" + k)
                    .attr("x", function(d,i) {return (oR*(3*(k+1)-1) - 0.6*oR*(k-1) + signSide*oR*2.5*Math.cos((i-1)*45/180*3.1415926));})
                    .attr("y", function(d,i) {return ((h+oR)/3 + signSide*oR*2.5*Math.sin((i-1)*45/180*3.1415926));})
                    .attr("font-size", function(){
                            return (k==i)?12:6;
                        })
                    .style("opacity",function(){
                            return (k==i)?1:0;
                        });
                     
                t.selectAll(".childBubble" + k)
                    .attr("cx", function(d,i) {return (oR*(3*(k+1)-1) - 0.6*oR*(k-1) + signSide*oR*2.5*Math.cos((i-1)*45/180*3.1415926));})
                    .attr("cy", function(d,i) {return ((h+oR)/3 + signSide*oR*2.5*Math.sin((i-1)*45/180*3.1415926));})
                    .attr("r", function(){
                            return (k==i)?(oR*0.55):(oR/3.0);               
                    })
                    .style("opacity", function(){
                            return (k==i)?1:0;                  
                        }); 
            }                   
        }
     
    window.onresize = resetBubbles;
</script>

<div>
<!--<p style="margin-left:400px;color:#FFFFFF;" >
Top/head: 
Initial impression after application, which evaporates quickly 
 
Middle/heart:
Emerges after top notes, usually mellow and “rounded”
 
Base: 
Emerges after middle notes, brings depth and solidity to a perfume
</p>-->
</div>
</html> 
