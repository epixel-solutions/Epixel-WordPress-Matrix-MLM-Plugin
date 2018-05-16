<div class="printful-setting-group">

    <h2><?php echo esc_html($title); ?></h2>
    <p><?php echo esc_html($description); ?></p>

	<?php if ( !empty( $settings ) ) : ?>

        <table class="form-table">
            <tbody>
                <?php foreach($settings as $key => $setting) : ?>

                <?php
                    if($setting['type'] == 'title') {
                        continue;
                    }
                ?>

                <tr valign="top">

                    <th scope="row" class="titledesc">
                        <span class="woocommerce-help-tip"></span>
                        <label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($setting['title']); ?></label>
                    </th>

                    <td class="forminp">
                        <fieldset>
                            <legend class="screen-reader-text"><span><?php echo esc_html($setting['title']); ?></span></legend>

                            <?php if ( $setting['type'] == 'text' ) : ?>

                                <input class="input-text regular-input" type="text" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" value="<?php echo esc_html($setting['value']); ?>" placeholder="">

                            <?php elseif ($setting['type'] == 'checkbox') : ?>

                                <label for="<?php echo esc_attr($key); ?>">
                                <input class="" type="checkbox" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($setting['value']); ?>" <?php if($setting['value'] == 'yes') { echo 'checked="checked"'; } ?>><?php echo esc_html($setting['label']); ?></label><br>

                            <?php elseif ($setting['type'] == 'checkbox-group') : ?>

                                <?php foreach ( $setting['items'] as $checkbox_key => $item ) : ?>

                                    <label for="<?php echo esc_attr($checkbox_key); ?>" class="checkbox-group-item">
                                    <input class="" type="checkbox" name="<?php echo esc_attr($key) . '[]'; ?>" id="<?php echo esc_attr($checkbox_key); ?>" value="<?php echo esc_attr($checkbox_key); ?>" <?php if ( $item['value'] == 'yes' ) {echo 'checked="checked"';} ?>><?php echo wp_kses_post( $item['label'] ); ?></label><br>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </fieldset>
                    </td>
                </tr>

                <?php endforeach; ?>
            </tbody>
        </table>

	<?php endif; ?>

</div>