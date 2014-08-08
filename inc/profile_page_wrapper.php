<h3>LinkedIn Integration</h3>

<?php
# check if data on user exists
if( !empty( $user_options->data) ) :
    print_r( $user_options->data );
?>
<table class="form-table">
    <tbody>
        <tr>
            <th scope="row">Skills</th>
            <td><?php echo $user_options->data->skills; ?></td>
        </tr>
        <tr>
            <th scope="row">Languages</th>
            <td><?php echo $user_options->data->languages; ?></td>
        </tr>
    </tbody>
</table>


<?php
# display button to receive data from LinkedIn
else :
?>
    <a class="button-secondary" href="<?php echo "https://www.linkedin.com/uas/oauth2/authorization?response_type=code&client_id={$options['linkedin_api_key']}&scope=r_fullprofile&state={$state}&redirect_uri={$uri}" ?>">
        <?php _e("Connect to LinkedIn");?>
    </a>
<?php
endif;
?>