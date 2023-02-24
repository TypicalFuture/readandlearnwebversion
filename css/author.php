<?php
    header('Content-type: text/css; charser: UTF-8');
?>

@font-face{
    font-family: "Intro";
    src: url("../schrifti/Intro-Bold.otf") format("opentype");
}
@font-face{
    font-family: "Intro-Regular";
    src: url("../schrifti/Intro-Regular.otf") format("opentype");
}
@font-face{
    font-family: "Intro-Light";
    src: url("../schrifti/Intro-Light.otf") format("opentype");
}
@font-face{
    font-family: "Intro-Black";
    src: url("../schrifti/Intro-Black.otf") format("opentype");
}

body{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 98vh;
    font-family: intro light, sans-serif;
    background-color: #F3F9FB;
    background-size: cover;
    background-repeat: no-repeat;
    font-falily: "Intro-Regular"
}


.form-signinl{
    margin: auto;
    width: 500px;
    height: 600px;
    background: #FFFFFF;
    border: 1px solid #FFF;
    align-items: center;
    box-shadow: 0px 0px 30px rgba(229, 229, 229, 0.42);
}

.reg{
    font-family: "Intro-Regular";
    font-style: normal;
    font-weight: normal;
    font-size: 40px;
    line-height: 40px;
    text-align: center;
    margin-top: 75px;
    margin-bottom: 80px;
}

.inputtt{
    width: 260px;
    height: 220px;
    margin: auto;
    margin-bottom: 56px;
    font-family: "Intro-Regular";
}

p{
    font-size: 18px;
    text-align: left;
    color: #5c5c5c;
    margin-bottom: 0px;
}

.inputt{
    margin-bottom: 45px;
    width: 100%;
    height: 30px;
    font-family: "Intro-Regular";
    //border-radius: 30px;
    border-style: none;
    border-bottom: solid 1px #2580A3;
    background-color: #fcfcfc;;
    outline-style: none;
}

.inputt:focus{
    background: #F3F9FB;
}

.allert{
    color: #ff0000;
    text-align: center;
}
  
.outline-btn{
    position: absolute;
    width: 193px;
    height: 70px;
    margin-left: 158px;
    margin-right: 140px;
}

.butreg{
    outline-style: none;
    letter-spacing: 0.56px;
    font-family: "Intro-Regular";
    font-style: normal;
    font-weight: normal;
    font-size: 14px;
    line-height: 14px;
    text-transform: uppercase;
    width: 184px;
    height: 52px;
    color: #FFF;
    border: none;background: linear-gradient(to left, #2661A6, #409BC2, #2661A6);
    background-size: 200%;
    transition: .5s;
    position: absolute;
    letter-spacing: 0.06em;
}

.butreg:hover{
    /*border: 1px solid #1F186E;
    background: none;
    background-color: #F3F9FB;;
    color: #2E2B1E;*/
    background-position: right;
    letter-spacing: 0.07em;
    cursor: pointer;
}

.outer{
    margin-top: 9px;
    margin-left: 9px;
    width: 184px;
    height: 52px;
    border: 1px solid #1F186E;
    box-sizing: border-box;
}
