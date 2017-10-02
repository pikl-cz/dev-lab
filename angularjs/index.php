 <!DOCTYPE html>
<html lang="en-US">
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
<body>

<div ng-app="">
  <p>Name : <input type="text" ng-model="name"></p>
  <h1>Hello {{name}}</h1>
   
  <p>My first expression: {{ 5 + 5 }}</p>



  <div ng-init="myCol='lightblue'">

<input style="background-color:{{myCol}}" ng-model="myCol" value="{{myCol}}">

</div>

</div>


</body>
</html> 