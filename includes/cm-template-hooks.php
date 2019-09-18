<?php

add_action( 'woocommerce_account_parent-details_endpoint', 'cm_parent_details_page' );
add_action( 'woocommerce_account_family-details_endpoint', 'check_family_page' );
add_action( 'woocommerce_account_adult-details_endpoint', 'cm_adult_details_page' );
add_action( 'woocommerce_account_privacy-and-consent_endpoint', 'udf_consent_summary_page' );

