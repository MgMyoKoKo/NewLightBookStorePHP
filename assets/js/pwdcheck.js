function validate(){

            var a = document.getElementById("sgnpwd").value;
            var b = document.getElementById("sgnconpwd").value;
            if (a!=b) {
               alert("Passwords do no match");
               return false;
            }
        }