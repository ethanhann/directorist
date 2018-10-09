 <?php
 $fields = $atbdp_query->posts;
            if (isset($_POST['term_id'])){
            foreach ($fields as $post){
                setup_postdata($post);
                $post_id = $post->ID;
                $cf_required = get_post_meta(get_the_ID(), 'required', true);
                ?>
                <div class="form-group" id="custom_field_for_cat">
                    <script>
                        jQuery(document).ready(function($){
                            $('.my-color-field').wpColorPicker();
                        });
                    </script>

                    <label for=""><?php the_title(); ?><?php if($cf_required){echo '<span style="color: red"> *</span>'; }?></label>
                    <?php
                    $value = ['default_value'][0];
                    $cf_meta_default_val = get_post_meta(get_the_ID(), 'default_value', true);
                    global $wpdb;
                    // get the all values for edit and show for custom fields
                    $all_values = $wpdb->get_col( $wpdb->prepare( "
                                                SELECT pm.meta_value FROM {$wpdb->postmeta} pm
                                                LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
                                                WHERE pm.meta_key = '%d' 
                                            ", $post_id ) );
                    $value =  end($all_values);
                    if( isset( $post_id ) ) {
                        $cf_meta_default_val = $post_id[0];
                    }
                    $cf_meta_val = get_post_meta(get_the_ID(), 'type', true);
                    $cf_rows = get_post_meta(get_the_ID(), 'rows', true);
                    $cf_placeholder = get_post_meta(get_the_ID(), 'placeholder', true);

                    switch ($cf_meta_val){

                        case 'text' :
                            echo '<div>';
                            printf('<p style="font-style: italic">%s</p>', get_post_meta(get_the_ID(), 'instructions', true));

                            printf( '<input type="text" name="custom_field[%d]" class="form-control directory_field" placeholder="" value="%s"/>', $post->ID, esc_attr( $value ) );
                            echo '</div>';
                            break;
                        case 'textarea' :
                            printf('<p style="font-style: italic">%s</p>', get_post_meta(get_the_ID(), 'instructions', true));

                            printf( '<textarea class="form-control directory_field" name="custom_field[%d]" class="textarea" rows="%d" placeholder="%s">%s</textarea>', $post->ID, (int) $cf_rows,esc_attr( $cf_placeholder ), esc_textarea( $value ) );
                            break;
                        case 'radio':
                            $choices = get_post_meta(get_the_ID(), 'choices', true);
                            $choices = explode( "\n", $choices );
                            printf('<p style="font-style: italic">%s</p>', get_post_meta(get_the_ID(), 'instructions', true));
                            echo '<ul class="acadp-radio-list radio vertical">';
                            foreach( $choices as $choice ) {
                                if( strpos( $choice, ':' ) !== false ) {
                                    $_choice = explode( ':', $choice );
                                    $_choice = array_map( 'trim', $_choice );

                                    $_value  = $_choice[0];
                                    $_label  = $_choice[1];
                                } else {
                                    $_value  = trim( $choice );
                                    $_label  = $_value;
                                }

                                $_checked = '';
                                if( trim( $value ) == $_value ) $_checked = ' checked="checked"';

                                printf( '<li><label><input type="radio" name="custom_field[%d]" value="%s"%s>%s</label></li>', $post->ID, $_value, $_checked, $_label );
                            }
                            echo '</ul>';
                            break;

                        case 'select' :
                            $choices = get_post_meta(get_the_ID(), 'choices', true);
                            $choices = explode( "\n", $choices );
                            printf('<p style="font-style: italic">%s</p>', get_post_meta(get_the_ID(), 'instructions', true));
                            printf( '<select name="custom_field[%d]" class="form-control directory_field">', $post->ID );
                            if( ! empty( $field_meta['allow_null'][0] ) ) {
                                printf( '<option value="">%s</option>', '- '.__( 'Select an Option', 'advanced-classifieds-and-directory-pro' ).' -' );
                            }
                            foreach( $choices as $choice ) {
                                if( strpos( $choice, ':' ) !== false ) {
                                    $_choice = explode( ':', $choice );
                                    $_choice = array_map( 'trim', $_choice );

                                    $_value  = $_choice[0];
                                    $_label  = $_choice[1];
                                } else {
                                    $_value  = trim( $choice );
                                    $_label  = $_value;
                                }

                                $_selected = '';
                                if( trim( $value ) == $_value ) $_selected = ' selected="selected"';

                                printf( '<option value="%s"%s>%s</option>', $_value, $_selected, $_label );
                            }
                            echo '</select>';
                            break;

                        case 'checkbox' :
                            $choices = get_post_meta(get_the_ID(), 'choices', true);
                            $choices = explode( "\n", $choices );

                            $values = explode( "\n", $value );
                            $values = array_map( 'trim', $values );
                            printf('<p style="font-style: italic">%s</p>', get_post_meta(get_the_ID(), 'instructions', true));
                            echo '<ul class="acadp-checkbox-list checkbox vertical">';

                            foreach( $choices as $choice ) {
                                if( strpos( $choice, ':' ) !== false ) {
                                    $_choice = explode( ':', $choice );
                                    $_choice = array_map( 'trim', $_choice );

                                    $_value  = $_choice[0];
                                    $_label  = $_choice[1];
                                } else {
                                    $_value  = trim( $choice );
                                    $_label  = $_value;
                                }

                                $_checked = '';
                                if( in_array( $_value, $values ) ) $_checked = ' checked="checked"';

                                printf( '<li><label><input type="hidden" name="custom_field[%s][]" value="" /><input type="checkbox" name="custom_field[%d][]" value="%s"%s>%s</label></li>', $post->ID, $post->ID, $_value, $_checked, $_label );
                            }
                            echo '</ul>';
                            break;
                        case 'url'  :
                            echo '<div>';
                            printf('<p style="font-style: italic">%s</p>', get_post_meta(get_the_ID(), 'instructions', true));
                            printf( '<input type="text" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $post->ID, esc_attr( $cf_placeholder ), esc_url( $value ) );
                            echo '</div>';
                            break;

                        case 'date'  :
                            echo '<div>';
                            printf('<p style="font-style: italic">%s</p>', get_post_meta(get_the_ID(), 'instructions', true));
                            printf( '<input type="date" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $post->ID, esc_attr( $cf_placeholder ), esc_attr( $value ) );
                            echo '</div>';
                            break;

                        case 'color'  :
                            echo '<div>';
                            printf('<p style="font-style: italic">%s</p>', get_post_meta(get_the_ID(), 'instructions', true));
                            printf( '<input type="text" name="custom_field[%d]" class="my-color-field" value="%s"/>', $post->ID, $value );
                            echo '</div>';
                            break;
                    }
                    ?>
                </div>
                <?php
            }
            wp_reset_postdata();
            wp_reset_query();
            }