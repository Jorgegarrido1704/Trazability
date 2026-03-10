<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">

<style>

@page{
    size: letter;
    margin: 0.5in;
}

body{
    margin:0;
    font-family: Arial, sans-serif;
}

.sheet{
    width:8.5in;
    height:11in;
    display:flex;
    flex-direction:column;
    justify-content:space-evenly;
    align-items:center;
}

.label{
    width:6in;
    height:4in;
    border:2px solid #5a6a73;
    border-radius:12px;
    overflow:hidden;
}

.header{
    background:#18a64a;
    text-align:center;
    font-weight:bold;
    padding:8px;
    font-size:18px;
    height:0.75in;
}
.header2{
    background:yellow;
    text-align:center;
    font-weight:bold;
    padding:8px;
    font-size:18px;
    height:0.75in;
}

.row{
    display:flex;
    border-top:1px solid #777;
    height:0.5in;
}

.left{
    width:1.2in;
    border-right:1px solid #777;
    padding:5px;
    font-size:14px;
}

.right{
    flex:1;
}

.notes{
    border-top:1px solid #777;
    height:1.75in;
    padding:6px;
    font-size:14px;
}

</style>
</head>

<body>

<div class="sheet">

    <div class="label">
        <div class="header">Certificate of Conformity</div>

        <div class="row">
            <div class="left">Part Number(s)</div>
            <div class="right"></div>
        </div>

        <div class="row">
            <div class="left">NCR(s)</div>
            <div class="right"></div>
        </div>

        <div class="row">
            <div class="left">SDR(s)</div>
            <div class="right"></div>
        </div>

        <div class="notes">
            <b>Notes:</b>
        </div>
    </div>


    <div class="label">
        <div class="header2">Part Return Form</div>

        <div class="row">
            <div class="left">Part Number(s)</div>
            <div class="right"></div>
        </div>

        <div class="row">
            <div class="left">NCR(s)</div>
            <div class="right"></div>
        </div>

        <div class="row">
            <div class="left">RMA(s)</div>
            <div class="right"></div>
        </div>

        <div class="notes">
            <b>Notes:</b>
        </div>
    </div>

</div>

</body>
</html>