<?php 

function die_after_return($boolean) {
    return false;
    if ($boolean) die;
}

function die_before_return($boolean) {
    if ($boolean) die;
    return false;
}

?>

Here's what I'm expecting:

Die after return false:
<?php die_after_return(false); ?>

This text will definitely show up

Die after return true:
<?php die_after_return(true); ?>

This text will probably show up

Die before return false:
<?php die_before_return(false); ?>

This text will definitely show up

Die before return true:
<?php die_before_return(true); ?>

This text will definitely not show up