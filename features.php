<?php //NEW FEATURES FILE  ?>
<script>var welcome = '<?php echo $welcome;?>';</script>
<div class="getting_wpr">

    <div class="pending_message yGrad">
        <span class="message">Check Our New Features</span>
    </div>

    <h2>Welcome to v2.0 of the ContactUs.com Contact Form Plugin</h2>
    <p class="sub-title">We made it easy to sign up for a free ContactUs.com account from within our WordPress plugin</p>

    <div class="getting_features">
        <div class="features">
            <div class="premium_features">
                <div class="head yGrad">
                    <h3>New Features</h3>
                </div>

                <div class="feature">
                    <p><span class="bold">One-Step Registration</span>Sign up directly from our plugin</p>
                    <?php if($userStatus == 'inactive'): ?><a class="upgrade orGrad gotoreg" href="#">Check</a><?php endif;?>
                </div>
                <div class="feature">
                    <p><span class="bold">Instant Activation </span>Check your email after registration</p>
                    <?php if($userStatus == 'inactive'): ?><a class="upgrade orGrad gotoreg" href="#">Check</a><?php endif;?>
                </div>
                <div class="feature">
                    <p><span class="bold">Easy Login</span>Connect your account for future sessions</p>
                    <a class="upgrade orGrad gotologin" href="#">Check</a>
                </div>
                <div class="feature">
                    <p><span class="bold">Form Settings</span>Update main form settings within the plugin</p>
                    <a class="upgrade orGrad <?php echo ($userStatus == 'active') ? 'gotosettingsa' : 'gotosettings'; ?>" href="#">Check</a>
                </div>
            </div>

        </div>
        
    </div>
    <form name="welcome" id="welcome" action="admin.php?page=cUs_form_plugin" method="post">
        <label class="labelform" for="show_welcome">Don't show this window at startup again.</label>
        <input name="show_welcome" id="show_welcome" type="checkbox" <?php echo ($welcome =='off')? 'checked="checked"':'';?> />
        <input type="hidden" name="welcome_status" id="welcome_status" />
    </form>
    <a href="javascript:;" class="callout-button" alt="Close" title="Close"></a>

</div>