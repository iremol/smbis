/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var $ = function (id) {
    return document.getElementById(id);
};
var byclass = function (classname) {
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
var enableFields = function(){
    var f = byclass("e");
    for (var i=0;i<f.length;i++){
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
    }else if(password.value == "" && re_password.value==""){
        password.style.border = "1px solid #999999";
        password.style.background = "white";
        re_password.style.border = "1px solid #999999";
        re_password.style.background = "white";
    }
    else {
        re_password.setCustomValidity('');
        re_password.style.background = "lightgreen";
        re_password.style.border = "0px";
        password.style.background = "lightgreen";
        password.style.border = "0px";
        
    }
}



var bcover = function (evt) {
    //var element = $(id);
    var element = evt.currentTarget;
    element.style.background = "#333";
    element.firstChild.style.color = "#fff";
};
var bcout = function (evt) {
    var element = evt.currentTarget;
    element.style.background = "#eee";
    element.firstChild.style.color = "#666";
};

var bcclick = function(evt){
    var element = evt.currentTarget;
    element.style.background = "#eee";
    element.firstChild.style.color = "#666";
};

var showDiv = function(evt){
    var element = evt.currentTarget;
    if(element.id === "h"){
        document.getElementById("history").style.display="block";
        document.getElementById("current").style.display="none";
        document.getElementById("future").style.display="none";
    }
    else if(element.id === "c"){
         document.getElementById("history").style.display="none";
        document.getElementById("current").style.display="block";
        document.getElementById("future").style.display="none";
    }
    else{
         document.getElementById("history").style.display="none";
        document.getElementById("current").style.display="none";
        document.getElementById("future").style.display="block";
    }
};


