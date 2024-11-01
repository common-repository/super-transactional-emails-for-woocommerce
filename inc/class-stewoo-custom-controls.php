<?php

class Stewoo_Range_Customize_Control extends WP_Customize_Control
{
    public  $unit = '' ;
    public function render_content()
    {
        ?>
	<label>
	  <?php 
        
        if ( !empty($this->label) ) {
            ?>
		  <span class="customize-control-title"><?php 
            echo  esc_html( $this->label ) ;
            ?></span>
	  <?php 
        }
        
        
        if ( !empty($this->description) ) {
            ?>
		  <span class="description customize-control-description"><?php 
            echo  $this->description ;
            ?></span>
	  <?php 
        }
        
        ?>
	  <input type="<?php 
        echo  esc_attr( $this->type ) ;
        ?>" <?php 
        $this->input_attrs();
        ?> value="<?php 
        echo  esc_attr( $this->value() ) ;
        ?>" <?php 
        $this->link();
        ?> />
	  <span class="stewoo-unit"><?php 
        echo  esc_attr( $this->value() . $this->unit ) ;
        ?></span>
	</label>
	<?php 
    }

}
class Stewoo_Customizer_Send_Email_Control extends WP_Customize_Control
{
    public function render_content()
    {
        $current_user = wp_get_current_user();
        echo  '<div class="control-wrap">' ;
        echo  '<p><input type="text" name="send_test_email_to" value="' . esc_attr( $current_user->user_email ) . '" /></p>' ;
        echo  '<a href="#" title="' . __( 'Send Test Email', 'stewoo' ) . '" class="stewoo_send_test_email button">' . __( 'Send Test Email', 'stewoo' ) . '</a>' ;
        echo  '<div class="stewoo_send_test_email_message"></div>' ;
        echo  '</div>' ;
        echo  '<style>
		.stewoo_send_test_email.loading + .stewoo_send_test_email_message{
			position: relative;
			top: 3px;
			display: inline-block;
		}
		.stewoo_send_test_email.loading + .stewoo_send_test_email_message:after{
			content: "";
			width: 20px;
			height: 20px;
			display: inline-block;
			border: dashed 1px transparent;
			margin-left: 6px;
			vertical-align: middle;
			border-radius: 50%;
			border: dashed 1px #2d2d2d;
			animation: spin 2s linear infinite;
			content: "@";
			text-align: center;
			color: #2d2d2d;
		}
		.stewoo_send_test_email_message.updated{
			font-weight: bold;
			padding: 11px;
		}
		.stewoo_send_test_email_message.error{
			font-weight: bold;
			padding: 11px;
		}
		</style>' ;
    }

}