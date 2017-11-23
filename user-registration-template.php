<?php
/**
 * Template Name: User Registration 
 *
 * @package User Registration
 * @author Dinesh Chouhan
 */

get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				// get_template_part( 'template-parts/page/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				// if ( comments_open() || get_comments_number() ) :
				// 	comments_template();
				// endif;
				
				$wp_roles = wp_roles();
				$user_roles = $wp_roles->roles;
				unset( $user_roles['administrator'] );
				?>

				<div class="wp-user-registration-form">
					<form>
						<label><b>First Name</b></label>
						<input type="text" placeholder="Enter First Name..." name="first-name" required>

						<label><b>Last Name</b></label>
						<input type="text" placeholder="Enter Last Name..." name="last-name" required>

						<label><b>Username</b></label>
						<input type="text" placeholder="Enter Username..." name="username" required>

						<label><b>Email</b></label>
						<input type="email" placeholder="Enter Email..." name="email" required>

						<label><b>Password</b></label>
						<input type="password" placeholder="Enter Password..." name="password" required>
						
						<label><b>User Role</b></label>
						<select name="user-roles">
							<?php foreach ( $user_roles as $key => $value ) { ?>
								<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $value['name'] ); ?></option>
							<?php } ?>
						</select>

						<div class="clearfix">
							<button type="submit" class="signupbtn">Register Me</button>
						</div>
					</form>
				</div>
				
			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer();
