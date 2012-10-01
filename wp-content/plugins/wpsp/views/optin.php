<?php
if ($postMeta['shared']['optInProvider'] != '(none)' AND $postMeta['shared']['optInUsername'] != '' AND $postMeta['shared']['optInListID'] != '') { 
?>
    <form action="<?php echo $postMeta['optin']['postURL']; ?>" method="post" class="newsletter">
        <?php if ($postMeta['optin']['success'] != '') { ?>
            <p class="message"><?php echo $postMeta['optin']['success']; ?></p>
        <?php } ?>
        <?php if ($postMeta['optin']['error'] != '') { ?>
            <p class="error"><?php echo $postMeta['optin']['error']; ?></p>
        <?php } ?>
        <?php if (trim($postMeta['shared']['optInHeaderText']) != '') { ?><div class="optin-head"><?php echo $postMeta['shared']['optInHeaderText']; ?></div><?php } ?>
        <?php if (trim($postMeta['shared']['TextAboveoptIn']) != '') { ?><p><?php echo $postMeta['shared']['TextAboveoptIn']; ?></p><?php } ?>
        <p>
            <input type="text" name="<?php echo $postMeta['optin']['name']; ?>" value="First Name" class="text name" />
        </p>
        <p>
            <input type="text" name="<?php echo $postMeta['optin']['email']; ?>" value="Email" class="text email" />
        </p>
        <p class="submit">
            <input type="submit" name="submit" value="<?php echo $postMeta['shared']['ButtonText']; ?>" />
            <input type="hidden" name="<?php echo $postMeta['optin']['listID']; ?>" value="<?php echo $postMeta['shared']['optInListID']; ?>" />
            <?php if ($postMeta['optin']['additionalFields'] != '') echo $postMeta['optin']['additionalFields']; ?>
        </p>                    
    </form>
<?php
}
?>