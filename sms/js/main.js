/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var $ = function(id) {
	return document.getElementById(id);
};
var byclass = function(classname) {
	return document.getElementsByClassName(classname);
};
function isNumber(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if ((charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))) {
		return false;
	}
	return true;
}
var enableFields = function() {
	var f = byclass("e");
	for (var i = 0; i < f.length; i++) {
		f[i].disabled = false;
	}
}

function validatePassword() {
	var password = document.getElementById("password");
	var re_password = document.getElementById("re-password");

	if (password.value != re_password.value) {
		re_password.setCustomValidity("Passwords Don't Match");
		re_password.style.background = "#ffcccc";
		password.style.background = "#ffcccc";
		re_password.nextElementSibling.firstChild.nodeValue = "Passwords mismatch.";
	} else if (password.value == "" && re_password.value == "") {
		password.style.border = "1px solid #999999";
		password.style.background = "white";
		re_password.style.border = "1px solid #999999";
		re_password.style.background = "white";
	} else {
		re_password.setCustomValidity('');
		re_password.style.background = "lightgreen";
		re_password.style.border = "0px";
		password.style.background = "lightgreen";
		password.style.border = "0px";
		re_password.nextElementSibling.firstChild.nodeValue = "*";

	}
}
/**
 * 
 * 
 * 
 * @returns {undefined}
 */
function validateEmail(evt) {
	var element = evt.currentTarget; // retrieve the html element as source
										// of event.
	if (!(/^\w+([\.-]?\w)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(element.value))) { // test
																					// the
																					// correctness
																					// on
																					// the
																					// entered
																					// email.
		(element.nextElementSibling).firstChild.nodeValue = "Incorrect email.";
	} else {
		(element.nextElementSibling).firstChild.nodeValue = "*";

	}
}

var bcover = function(evt) {
	// var element = $(id);
	var element = evt.currentTarget;
	element.style.background = "#050";
	element.firstChild.style.color = "#6f6";
};
var bcout = function(evt) {
	var element = evt.currentTarget;
	element.style.background = "#6f6";
	element.firstChild.style.color = "#050";
};

var bcclick = function(evt) {
	var element = evt.currentTarget;
	element.style.background = "#6f6";
	element.firstChild.style.color = "#050";
}

var showDiv = function(evt) {
	alert("hello");
	var element = evt.currentTarget;
	if (element.id == "history") {
		document.getElementById("history").style.display = "block";
	}
}
var expandMenu = function(evt) {
	var element = evt.currentTarget; // the element generating the event
	(element.childNodes)[1].style.display="block"; //select the 2nd elements of the node list
}
var contractMenu = function(evt) {
	var element = evt.currentTarget; // the element generating the event
	(element.childNodes)[1].style.display="none"; //select the 2nd elements of the node list
}
function w3_open() {
	document.getElementById("content").style.marginLeft = "25%";
	document.getElementById("sidenav").style.width = "25%";
	document.getElementById("sidenav").style.display = "block";
	document.getElementById("openNav").style.display = 'none';
}
function w3_close() {
	document.getElementById("content").style.marginLeft = "0%";
	document.getElementById("sidenav").style.display = "none";
	document.getElementById("openNav").style.display = "inline-block";
}

Date.prototype.addDays = Date.prototype.addDays || function( days ) {
    return this.setTime( 864E5 * days + this.valueOf() ) && this;
};
String.prototype.reverse = function(){
	return (this.split("/")).reverse().join(''); 
};
String.prototype.formatDate= function (){
	var dtArr=this.split("/");
	var temp = dtArr[0];
	dtArr[0] = dtArr[1];
	dtArr[1] = dtArr[2];
	dtArr[2] = temp;
	return dtArr.join('/');
};
var checkInput = function(e1 , e2){
	var element_one = $(e1);
	var element_two = $(e2);
	if(element_one.value == "Select") {
		alert("Enter value");
	}
}
window.onload = function() {
	var arr = byclass("c");
	for (var i = 0; i < arr.length; i++) {
		arr[i].addEventListener("mouseover", bcover, false);
		arr[i].addEventListener("mouseout", bcout, false);
		arr[i].addEventListener("mouseout", bcclick, false);
	}

	var e = byclass("submenu");
	for(var i =0;i<e.length; i++){
		e[i].addEventListener("mouseover", expandMenu, false);
		e[i].addEventListener("mouseout", contractMenu, false);
	}

	$("password").addEventListener("change", validatePassword, false);
	$("re-password").addEventListener("input", validatePassword, false);
	$("h").addEventListener("click", showDiv, false);
	// $("edit").addEventListener("click",enableFields(),false);
	// $("li1").addEventListener("mouseout",backgroundChange,false);
	// $("li1").addEventListener("mouseover",test,false);
	// $("li1").onclick = backgroundChange($("li1"), "#333", "#fff");
	// $("li1").onmouseout = backgroundChange( $("li1"),"#eee","#333");
};

