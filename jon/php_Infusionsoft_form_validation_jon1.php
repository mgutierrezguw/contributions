<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grab File #1</title>
</head>
<body>

<div class="infusion-submit">
    <button style="display:none;" class="infusion-recaptcha" id="recaptcha_893ed165dc36148ac57b5ebd4e2ff8cb" type="submit">Submit</button>
</div>

<button type="button" class="infusion-submit button success" id="report">Submit</button>

<script>

    var form = document.querySelector("#inf_form_893ed165dc36148ac57b5ebd4e2ff8cb");
    var reportButton = document.querySelector("#report");

    reportButton.addEventListener("click", function() {
        var reportVal = form.reportValidity();
        if(reportVal){
            document.getElementById("recaptcha_893ed165dc36148ac57b5ebd4e2ff8cb").click();
        }
    });

</script>
    
</body>
</html>