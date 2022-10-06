<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>


<div class="spacer"></div>
<div id="square_wrap">
    <div class="square">This is a square</div>
</div>
<style>
.spacer{
    height:2000px;
}
.square{
    opacity: 0;
    transform: scale(1.2);
    background:blue;
    height:300px;
    width:300px;
}
@media (prefers-reduced-motion: no-preference) {
    .square {
    transition: opacity 1s ease, transform 1s ease;
    }
}
.square_transition {
    opacity: 1;
    transform: none;
}
</style>
<script>
const items = document.getElementsByClassName("square_transition");
Array.from(items).forEach(element => {
    element.classList.remove("square_transition");
});
const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
    if (entry.isIntersecting) {
        entries[0].target.querySelector('div').classList.add('square_transition');
        return;
    }
    //entries[0].target.querySelector('.container').classList.remove('container_transition');
    });
});
observer.observe(document.querySelector('#square_wrap'));
var delayInMilliseconds = 500; //.5 second
setTimeout(function() {
    var element = document.getElementById("square_wrap");
    // element.querySelector('div').classList.add('square_transition');
}, delayInMilliseconds);
</script>
    

</body>
</html>