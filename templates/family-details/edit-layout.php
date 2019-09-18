<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<div class="row bs-wizard steps" style="border-bottom:0;">

    <div class="col-lg-4 col-xs-4 bs-wizard-step active">
        <div class="text-center bs-wizard-stepnum">1</div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="#" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center">Details of Child</div>
    </div>

    <div class="col-lg-4 col-xs-4 bs-wizard-step disabled"><!-- complete -->
        <div class="text-center bs-wizard-stepnum">2</div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="#" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center">In Case of Emergency</div>
    </div>

    <div class="col-lg-4 col-xs-4 bs-wizard-step disabled"><!-- complete -->
        <div class="text-center bs-wizard-stepnum">3</div>
        <div class="progress"><div class="progress-bar"></div></div>
        <a href="#" class="bs-wizard-dot"></a>
        <div class="bs-wizard-info text-center">Consent</div>
    </div>

</div>

<form method="post" enctype="multipart/form-data">
<div class="form-container">


    <div class="step" id="step_1">
        <?php include 'info-form.php'; ?>

    </div>

    <div class="step" id="step_2">
        <?php include 'emergncy-form.php'; ?>

    </div>
    <div class="step" id="step_3">

        <?php include 'agree-form.php'; ?>
    </div>


</div>

    <input type="hidden" name="route_name" value="child_info"/>
</form>


<!---->
<?php
//
//$current_uri = home_url( add_query_arg( NULL, NULL ) ); ?>
<!---->
<!--<ul class="nav nav-tabs">-->
<!--    <li class="--><?//=$route_name == 'home' ? 'active' : ''?><!--">-->
<!--        <a data-toggle="tab" href="--><?//=isset($child_info) ? home_url('my-account/family-details/' . $child_info->id ) : home_url('my-account/family-details/add') ?><!--">Info</a>-->
<!--    </li>-->
<!---->
<!--    --><?php //if (isset($child_info)) : ?>
<!--        <li class="--><?//= $route_name == 'emergency' ? 'active' : '' ?><!--">-->
<!--            <a data-toggle="tab" href="--><?//=home_url('my-account/family-details/' . $child_info->id . '/emergency'); ?><!--">Emergency</a>-->
<!--        </li>-->
<!--        <li class="--><?//= $route_name == 'agree' ? 'active' : '' ?><!--">-->
<!--            <a data-toggle="tab" href="--><?//=home_url('my-account/family-details/' . $child_info->id . '/agree'); ?><!--">Consent</a>-->
<!--        </li>-->
<!--        <li class="--><?//= $route_name == 'classes' ? 'active' : '' ?><!--">-->
<!--            <a data-toggle="tab" href="--><?//=home_url('my-account/family-details/' . $child_info->id . '/classes'); ?><!--">Classes</a>-->
<!--        </li>-->
<!--    --><?php //endif; ?>
<!--</ul>-->
<!---->
<!--<div class="tab-content">-->
<!--    <div id="home" class="tab-pane fade--><?//=$route_name == 'home' ? ' in active' : ''?><!--">-->
<!--        <h3>Child details</h3>-->
<!--        --><?php //if ($route_name == 'home') : include('info-form.php'); endif; ?>
<!--    </div>-->
<!--    <div id="emergency" class="tab-pane fade--><?//=$route_name == 'emergency' ? ' in active' : ''?><!--">-->
<!--        <h3>Emergency contacts</h3>-->
<!--        --><?php //if ($route_name == 'emergency') : include('emergncy-form.php'); endif; ?>
<!--    </div>-->
<!--    <div id="agree" class="tab-pane fade--><?//=$route_name == 'agree' ? ' in active' : ''?><!--">-->
<!--        <h3>Agreements</h3>-->
<!--        --><?php //if ($route_name == 'agree') : include('agree-form.php'); endif; ?>
<!--    </div>-->
<!--    <div id="classes" class="tab-pane fade--><?//=$route_name == 'classes' ? ' in active' : ''?><!--">-->
<!--        <h3>Classes</h3>-->
<!--        --><?php //if ($route_name == 'classes') :  include('classes-list.php'); endif; ?>
<!--    </div>-->
<!--</div>-->
