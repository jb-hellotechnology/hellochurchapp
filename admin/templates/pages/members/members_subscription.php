<?php
if (perch_member_has_tag('active')) {
	header("location:/");
}
perch_layout('header');
?>
<main class="wide center">
	<h2>Start Your Free Trial</h2>
	<script async src="https://js.stripe.com/v3/pricing-table.js"></script>
	<stripe-pricing-table pricing-table-id="prctbl_1Q7M8C2Q0ijOLx6YCHhYj0fQ"
	publishable-key="pk_test_3pJEvbOypC4IxEPxHc30DRfN"
	client-reference-id="<?= perch_member_get('reference') ?>"
	customer-email="<?= perch_member_get('email') ?>">
	</stripe-pricing-table>
</main>
<?php
perch_layout('footer');
?>