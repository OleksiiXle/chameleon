<style>

    .tDebug1{
        position: fixed; /* absolute | fixed | relative | static | inherit */
        left: 10%;
        top: 10%;
        width: 45%;
        height: 75%;
        /* table-layout:  fixed;*/
        overflow: auto ; /* полосы прокрутки | hidden | scroll | visible | inherit*/
        float: left;
    }
    .tDebug2{
        position: fixed; /* absolute | fixed | relative | static | inherit */
        left: 50%;
        top: 10%;
        width: 45%;
        height: 75%;
        /* table-layout:  fixed;*/
        overflow: auto ; /* полосы прокрутки | hidden | scroll | visible | inherit*/
        float: left;
    }

</style>

<?php

?>
<div class="tDebug1">
    <?= 'request';?>
    <pre>
        <?var_dump($d1);?>
    </pre>
</div>
<div class="tDebug2">
    <b><?= $mess?></b><br>
    <pre>
        <?var_dump($d2);?>
    </pre>
</div>

