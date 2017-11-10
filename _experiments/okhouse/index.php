<!DOCTYPE html>
<html lang="en-US">
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
		
		<style>
			/* Center the loader */
			#loader {
			  position: absolute;
			  left: 50%;
			  top: 50%;
			  z-index: 1;
			  width: 150px;
			  height: 150px;
			  margin: -75px 0 0 -75px;
			  border: 16px solid #f3f3f3;
			  border-radius: 50%;
			  border-top: 16px solid #3498db;
			  width: 120px;
			  height: 120px;
			  -webkit-animation: spin 2s linear infinite;
			  animation: spin 2s linear infinite;
			}

			@-webkit-keyframes spin {
			  0% { -webkit-transform: rotate(0deg); }
			  100% { -webkit-transform: rotate(360deg); }
			}

			@keyframes spin {
			  0% { transform: rotate(0deg); }
			  100% { transform: rotate(360deg); }
			}

			/* Add animation to "page content" */
			.animate-bottom {
			  position: relative;
			  -webkit-animation-name: animatebottom;
			  -webkit-animation-duration: 1s;
			  animation-name: animatebottom;
			  animation-duration: 1s
			}

			@-webkit-keyframes animatebottom {
			  from { bottom:-100px; opacity:0 } 
			  to { bottom:0px; opacity:1 }
			}

			@keyframes animatebottom { 
			  from{ bottom:-100px; opacity:0 } 
			  to{ bottom:0; opacity:1 }
			}

			#myDiv {
			  display: none;
			  text-align: center;
			}
		</style>
		
	</head>	
	<body onload="showPage()" style="margin:0">
		
		<div id="loader"></div>

		<div style="display:none;" id="myDiv" class="animate-bottom">
		  <div id="dataTest"></div>
		</div>

		<script>	
			/*
			 * Filter data
			 */			
			function filterData(data) {
				return filtered;
			}
			
			/*
			 * Get data
			 */
			function ajaxPersons() {
				//var xmlhttp = new XMLHttpRequest();
				if (window.XMLHttpRequest) {
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				} else {
					// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById("dataTest").innerHTML = filterData(this.responseText);
					}
				};			
				xmlhttp.open('GET', '/_experiments/okhouse/getData.php', true);
				xmlhttp.send();
			}
			ajaxPersons();

			/*
			 * Timer for loading to refresh
			 */
			var counter = 0;
			var i = setInterval(function(){

				ajaxPersons();

				counter++;
				if(counter === 10) {
					clearInterval(i);
				}
			}, 1000 * 2);

			/*
			 * Draw it nice
			 */	
			function showPage() {
				document.getElementById("loader").style.display = "none";
				document.getElementById("myDiv").style.display = "block";
			}						
		</script>
	</body>	
</html>