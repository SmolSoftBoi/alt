			<footer class="footer">
				<p>Copyright &copy; 2015 Kristian Matthews. All rights reserved.</p>
			</footer>
		</div>

		<!-- JavaScript -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script>
		<script src="<?= base_url('resources/js/bootstrap.min.js') ?>"></script>
		<script src="<?= base_url('resources/js/alt.js') ?>"></script>
		<script>
			$('[data-event]').on('click', function() {
				_gs('event', $(this).data('event'));
			});
		</script>

	</body>

</html>